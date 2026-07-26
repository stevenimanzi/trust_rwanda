<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PesapalService;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
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
            $orders = Order::where('transaction_id', $orderId)->get();
            
            if ($orders->isEmpty()) {
                \Log::warning('PaymentController: Order not found ' . $orderId);
                return redirect()->route('cart.index')->with('error', 'Order not found.');
            }

            $totalAmount = $orders->sum('total_amount');
            $firstOrder = $orders->first();
            $user = auth()->user() ?? (object)[
                'email' => 'buyer@trustrwanda.com',
                'name' => 'Buyer'
            ];

            \Log::info('PaymentController: Authenticating with Pesapal');
            $token = $this->pesapalService->authenticate();

            \Log::info('PaymentController: Registering IPN');
            $ipnId = \Illuminate\Support\Facades\Cache::remember('pesapal_ipn_id_' . (app()->isLocal() ? 'local' : 'prod'), 3600*24*30, function() use ($token) {
                // Determine IPN URL (Must be a public domain. If local, fake it so Pesapal API accepts it)
                $ipnUrl = app()->isLocal() ? 'https://trustrwanda.com/api/pesapal/ipn' : route('api.pesapal.ipn');
                
                try {
                    return $this->pesapalService->registerIPN($token, $ipnUrl);
                } catch (\Exception $e) {
                    \Log::error('IPN Registration failed: ' . $e->getMessage());
                    throw $e;
                }
            });

            // Limit amount if needed for testing (Optional)
            $testAmount = round($totalAmount, 2);

            // Construct billing address, replacing empty names with "Buyer" to prevent Pesapal validation errors
            // Ensure callback URL is a valid domain to prevent Pesapal from blocking the iframe load
            $callbackUrl = app()->isLocal() ? 'https://trustrwanda.com/payment/callback' : route('payment.callback');

            $orderData = [
                "id" => $orderId,
                "currency" => "RWF",
                "amount" => $testAmount,
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

            \Log::info('PaymentController: Redirecting to ' . $pesapalResponse['redirect_url']);
            return redirect()->away($pesapalResponse['redirect_url']);

        } catch (\Throwable $e) {
            Log::error('Pesapal Checkout Error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Payment Gateway Error: ' . $e->getMessage());
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
                Order::where('transaction_id', $orderId)->update([
                    'payment_status' => 'paid',
                    'payment_reference' => $trackingId
                ]);
                return redirect()->route('order.success')->with('success', 'Your payment was successful!');
            }

            if (strtolower($paymentStatusCode) === 'failed') {
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
                Order::where('transaction_id', $orderId)->update([
                    'payment_status' => 'paid',
                    'payment_reference' => $trackingId
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Pesapal IPN Processing Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }
}
