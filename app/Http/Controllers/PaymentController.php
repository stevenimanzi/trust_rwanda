<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use App\Services\PesapalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentController extends Controller
{
    public function __construct(
        protected PesapalService $pesapalService
    ) {
    }

    public function callback(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'OrderTrackingId' => ['required', 'string', 'max:255'],
            'OrderMerchantReference' => ['nullable', 'string', 'max:255'],
        ]);

        $trackingId = $validated['OrderTrackingId'];
        $fallbackMerchantReference =
            $validated['OrderMerchantReference'] ?? null;

        try {
            $payment = $this->fetchPaymentStatus(
                $trackingId,
                $fallbackMerchantReference
            );

            if ($payment['status'] === 'COMPLETED') {
                $updated = $this->updateOrdersStatus(
                    transactionId: $payment['merchant_reference'],
                    status: 'paid',
                    trackingId: $trackingId
                );

                if (!$updated) {
                    Log::warning('Pesapal callback order not found', [
                        'tracking_id' => $trackingId,
                        'merchant_reference' => $payment['merchant_reference'],
                    ]);

                    return redirect()
                        ->route('products.index')
                        ->with(
                            'error',
                            'Payment was received, but the related order could not be found.'
                        );
                }

                return redirect()
                    ->route('order.success')
                    ->with('message', 'Payment successful.');
            }

            if (in_array($payment['status'], ['FAILED', 'CANCELLED'], true)) {
                $this->updateOrdersStatus(
                    transactionId: $payment['merchant_reference'],
                    status: 'failed',
                    trackingId: $trackingId
                );

                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Payment failed or was cancelled.');
            }

            return redirect()
                ->route('order.success')
                ->with(
                    'message',
                    'Payment is being processed. The order will be updated after confirmation.'
                );
        } catch (Throwable $exception) {
            Log::error('Pesapal callback processing failed', [
                'tracking_id' => $trackingId,
                'merchant_reference' => $fallbackMerchantReference,
                'exception' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('products.index')
                ->with(
                    'message',
                    'The order was received, but payment confirmation is still pending.'
                );
        }
    }

    public function ipn(Request $request): JsonResponse
    {
        $validator = validator($request->all(), [
            'OrderTrackingId' => ['required', 'string', 'max:255'],
            'OrderMerchantReference' => ['nullable', 'string', 'max:255'],
            'OrderNotificationType' => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid Pesapal IPN payload', [
                'errors' => $validator->errors()->toArray(),
            ]);

            return response()->json([
                'status' => 400,
                'message' => 'Invalid notification payload.',
            ], 400);
        }

        $validated = $validator->validated();

        $trackingId = $validated['OrderTrackingId'];
        $fallbackMerchantReference =
            $validated['OrderMerchantReference'] ?? null;

        Log::info('Pesapal IPN received', [
            'tracking_id' => $trackingId,
            'merchant_reference' => $fallbackMerchantReference,
            'notification_type' =>
                $validated['OrderNotificationType'] ?? null,
        ]);

        try {
            $payment = $this->fetchPaymentStatus(
                $trackingId,
                $fallbackMerchantReference
            );

            if ($payment['status'] === 'COMPLETED') {
                $this->updateOrdersStatus(
                    transactionId: $payment['merchant_reference'],
                    status: 'paid',
                    trackingId: $trackingId
                );
            } elseif (
                in_array(
                    $payment['status'],
                    ['FAILED', 'CANCELLED'],
                    true
                )
            ) {
                $this->updateOrdersStatus(
                    transactionId: $payment['merchant_reference'],
                    status: 'failed',
                    trackingId: $trackingId
                );
            }

            return response()->json([
                'orderNotificationType' =>
                    $validated['OrderNotificationType'] ?? null,
                'orderTrackingId' => $trackingId,
                'orderMerchantReference' =>
                    $payment['merchant_reference'],
                'status' => 200,
            ], 200);
        } catch (Throwable $exception) {
            Log::error('Pesapal IPN processing failed', [
                'tracking_id' => $trackingId,
                'merchant_reference' => $fallbackMerchantReference,
                'exception' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => 500,
                'message' => 'Payment notification processing failed.',
            ], 500);
        }
    }

    /**
     * @return array{
     *     status: string,
     *     merchant_reference: string
     * }
     */
    private function fetchPaymentStatus(
        string $trackingId,
        ?string $fallbackMerchantReference
    ): array {
        $token = $this->pesapalService->authenticate();

        $response = $this->pesapalService->getTransactionStatus(
            $token,
            $trackingId
        );

        $status = strtoupper(
            (string) ($response['payment_status_description'] ?? 'PENDING')
        );

        $merchantReference =
            $response['merchant_reference']
            ?? $fallbackMerchantReference;

        if (!$merchantReference) {
            throw new \RuntimeException(
                'Pesapal merchant reference is missing.'
            );
        }

        return [
            'status' => $status,
            'merchant_reference' => (string) $merchantReference,
        ];
    }

    private function updateOrdersStatus(
        string $transactionId,
        string $status,
        string $trackingId
    ): bool {
        return DB::transaction(function () use (
            $transactionId,
            $status,
            $trackingId
        ): bool {
            $orders = Order::query()
                ->where('transaction_id', $transactionId)
                ->lockForUpdate()
                ->get();

            if ($orders->isEmpty()) {
                return false;
            }

            $currentPaymentStatus = $orders->first()->payment_status;

            // A confirmed payment must never be downgraded.
            if ($currentPaymentStatus === 'paid' && $status !== 'paid') {
                Log::warning('Attempted to downgrade paid order', [
                    'transaction_id' => $transactionId,
                    'tracking_id' => $trackingId,
                    'requested_status' => $status,
                ]);

                return true;
            }

            Order::query()
                ->where('transaction_id', $transactionId)
                ->update([
                    'payment_status' => $status,
                    'payment_method' => 'pesapal',
                ]);

            if ($status === 'paid') {
                $firstOrder = $orders->first();
                $totalAmount = $orders->sum('total_amount');

                Transaction::firstOrCreate(
                    [
                        'reference_id' => $trackingId,
                    ],
                    [
                        'user_id' => $firstOrder->user_id,
                        'amount' => $totalAmount,
                        'type' => 'payment',
                        'description' =>
                            'Pesapal payment for order '
                            . $transactionId,
                    ]
                );
            }

            return true;
        });
    }
}