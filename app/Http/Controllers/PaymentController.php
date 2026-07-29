<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PesapalService;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\AffiliateCommission;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected PesapalService $pesapalService
    ) {
    }

    public function checkout(string $orderId)
    {
        try {
            \Log::info('PaymentController: Starting checkout for order ' . $orderId);
            $orders = Order::where('transaction_id', $orderId)
                ->when(auth()->check(), fn ($query) => $query->where('user_id', auth()->id()))
                ->get();
            
            if ($orders->isEmpty()) {
                \Log::warning('PaymentController: Order not found ' . $orderId);
                return redirect()->route('cart.index')->with('error', 'Order not found.');
            }

            $totalAmount = $orders->sum('total_amount');
            $firstOrder = $orders->first();

            if ($firstOrder->payment_status === 'paid') {
                return redirect()->route('order.success');
            }

            if ($firstOrder->payment_status === 'failed') {
                return response()->view('store.payment_error', [
                    'orderId' => $orderId,
                    'message' => 'This payment attempt was not accepted. Your items were released so you can update the cart and try again.',
                ], 422);
            }

            if ($firstOrder->payment_reference && Str::isUuid($firstOrder->payment_reference)) {
                return $this->redirectToHostedCheckout($firstOrder->payment_reference);
            }

            $user = auth()->user() ?? (object)[
                'email' => 'buyer@trustrwanda.com',
                'name' => 'Buyer'
            ];

            \Log::info('PaymentController: Authenticating with Pesapal');
            $token = $this->pesapalService->authenticate();

            \Log::info('PaymentController: Registering IPN');
            $ipnId = \Illuminate\Support\Facades\Cache::remember('pesapal_ipn_id_' . config('pesapal.env'), 3600*24*30, function() use ($token) {
                $ipnUrl = config('pesapal.ipn_url') ?: route('api.pesapal.ipn');
                
                try {
                    return $this->pesapalService->registerIPN($token, $ipnUrl);
                } catch (\Exception $e) {
                    \Log::error('IPN Registration failed: ' . $e->getMessage());
                    throw $e;
                }
            });

            $paymentAmount = round($totalAmount, 2);

            $callbackUrl = config('pesapal.callback_url') ?: route('payment.callback');

            $orderData = [
                "id" => $orderId,
                "currency" => "RWF",
                "amount" => $paymentAmount,
                "description" => "Trust Rwanda Order " . $orderId,
                "callback_url" => $callbackUrl,
                "notification_id" => $ipnId,
                "billing_address" => [
                    "email_address" => $user->email ?? 'buyer@trustrwanda.com',
                    "phone_number" => $firstOrder->delivery_phone ?? $firstOrder->phone ?? '0000000000',
                    "country_code" => "RW",
                    "first_name" => !empty(trim($user->name)) ? $user->name : 'Buyer',
                    "middle_name" => "",
                    "last_name" => "Customer",
                    "line_1" => !empty(trim($firstOrder->delivery_address)) ? $firstOrder->delivery_address : 'Kigali',
                    "line_2" => "",
                    "city" => "Kigali",
                    "state" => "Kigali",
                    "postal_code" => "0000",
                    "zip_code" => "0000"
                ]
            ];

            \Log::info('PaymentController: Submitting Order to Pesapal');
            $pesapalResponse = $this->pesapalService->submitOrder($token, $orderData);

            if (!isset($pesapalResponse['redirect_url'])) {
                throw new \Exception('Failed to get redirect URL from Pesapal');
            }

            if (!empty($pesapalResponse['order_tracking_id'])) {
                Order::where('transaction_id', $orderId)->update([
                    'payment_reference' => $pesapalResponse['order_tracking_id'],
                ]);
            }

            \Log::info('PaymentController: Redirecting to Pesapal hosted checkout', [
                'order_id' => $orderId,
                'tracking_id' => $pesapalResponse['order_tracking_id'] ?? null,
            ]);

            session()->forget(['cart', 'ref_user_id']);

            return $this->redirectToHostedCheckoutUrl($pesapalResponse['redirect_url']);

        } catch (\Throwable $e) {
            Log::error('Pesapal Checkout Error: ' . $e->getMessage());
            $this->releaseFailedOrder($orderId);

            $message = str_contains(strtolower($e->getMessage()), 'amount exceeds limit')
                ? 'This order is above the payment limit enabled on the merchant account. Please try a smaller order or contact support.'
                : 'The secure payment service could not open. Your cart has been preserved; please try again.';

            return response()->view('store.payment_error', [
                'orderId' => $orderId,
                'message' => $message,
            ], 502);
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'OrderTrackingId' => 'required|string',
            'OrderMerchantReference' => 'required|string',
        ]);

        $trackingId = $validated['OrderTrackingId'];
        $orderId = $validated['OrderMerchantReference'];

        try {
            $token = $this->pesapalService->authenticate();
            $statusData = $this->pesapalService->getTransactionStatus($token, $trackingId);

            $paymentStatusCode = $statusData['payment_status_description'] ?? 'Pending';

            if (strtolower($paymentStatusCode) === 'completed') {
                $this->markPaid($orderId, $trackingId);
                return redirect()->route('order.success')->with('success', 'Your payment was successful!');
            }

            if (strtolower($paymentStatusCode) === 'failed') {
                $this->releaseFailedOrder($orderId);
                return redirect()->route('cart.index')->with('error', 'Payment failed. Please try again.');
            }

            return redirect()->route('order.success')->with('success', 'Your payment is pending confirmation.');

        } catch (\Exception $e) {
            Log::error('Pesapal Callback Error: ' . $e->getMessage());
            return redirect()->route('order.success')->with('success', 'Your order was placed, but we are verifying the payment.');
        }
    }

    public function ipn(Request $request)
    {
        Log::info('Pesapal IPN Received', $request->all());

        if (!$request->has('OrderTrackingId') || !$request->has('OrderMerchantReference')) {
            return response()->json(['status' => 'error', 'message' => 'Invalid IPN data'], 400);
        }

        $trackingId = $request->input('OrderTrackingId');
        $orderId = $request->input('OrderMerchantReference');

        try {
            $token = $this->pesapalService->authenticate();
            $statusData = $this->pesapalService->getTransactionStatus($token, $trackingId);

            $paymentStatusCode = $statusData['payment_status_description'] ?? 'Pending';

            if (strtolower($paymentStatusCode) === 'completed') {
                $this->markPaid($orderId, $trackingId);
            } elseif (strtolower($paymentStatusCode) === 'failed') {
                $this->releaseFailedOrder($orderId);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Pesapal IPN Processing Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }

    private function markPaid(string $orderId, string $trackingId): void
    {
        DB::transaction(function () use ($orderId, $trackingId) {
            $orders = Order::where('transaction_id', $orderId)->lockForUpdate()->get();
            if ($orders->isEmpty()) {
                return;
            }

            Order::where('transaction_id', $orderId)->update([
                'payment_status' => 'paid',
                'payment_reference' => $trackingId,
            ]);

            if (!Transaction::where('reference_id', $trackingId)->exists()) {
                Transaction::create([
                    'user_id' => $orders->first()->user_id,
                    'amount' => $orders->sum('total_amount'),
                    'type' => 'payment',
                    'description' => "Pesapal payment for order {$orderId}",
                    'reference_id' => $trackingId,
                ]);
            }
        });
    }

    private function releaseFailedOrder(string $orderId): void
    {
        DB::transaction(function () use ($orderId) {
            $orders = Order::where('transaction_id', $orderId)
                ->where('payment_status', 'pending')
                ->lockForUpdate()
                ->get();

            if ($orders->isEmpty()) {
                return;
            }

            $orderIds = $orders->pluck('id');
            OrderItem::whereIn('order_id', $orderIds)->get()->each(function (OrderItem $item) {
                Product::whereKey($item->product_id)->increment('stock_quantity', $item->quantity);
            });

            AffiliateCommission::whereIn('order_id', $orderIds)->update(['status' => 'failed']);
            Order::whereIn('id', $orderIds)->update(['payment_status' => 'failed']);
        });
    }

    private function redirectToHostedCheckout(string $trackingId): RedirectResponse
    {
        $url = 'https://pay.pesapal.com/iframe/PesapalIframe3/Index?'.http_build_query([
            'OrderTrackingId' => $trackingId,
        ]);

        return $this->redirectToHostedCheckoutUrl($url);
    }

    private function redirectToHostedCheckoutUrl(string $url): RedirectResponse
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        if ($host !== 'pay.pesapal.com') {
            throw new \RuntimeException('The payment provider returned an invalid checkout URL.');
        }

        return redirect()->away($url, 303, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }
}
