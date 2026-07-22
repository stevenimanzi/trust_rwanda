<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use App\Services\MtnMomoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MtnMomoPaymentController extends Controller
{
    public function pending(string $reference): View
    {
        $order = Order::where('payment_reference', $reference)->firstOrFail();
        $total = Order::where('transaction_id', $order->transaction_id)->sum('total_amount');

        return view('store.momo_pending', compact('order', 'reference', 'total'));
    }

    public function status(string $reference, MtnMomoService $momo): JsonResponse
    {
        $order = Order::where('payment_reference', $reference)->firstOrFail();

        try {
            $result = $momo->paymentStatus($reference);
            $status = $this->applyStatus($order->transaction_id, $reference, $result);

            return response()->json([
                'status' => $status,
                'reason' => $result['reason'] ?? null,
            ]);
        } catch (Throwable $exception) {
            Log::warning('MTN MoMo status check failed', [
                'reference' => $reference,
                'message' => $exception->getMessage(),
            ]);

            return response()->json(['status' => 'PENDING'], 202);
        }
    }

    public function callback(string $reference, MtnMomoService $momo)
    {
        $order = Order::where('payment_reference', $reference)->first();
        if (!$order) {
            return response()->noContent(404);
        }

        try {
            $this->applyStatus($order->transaction_id, $reference, $momo->paymentStatus($reference));
        } catch (Throwable $exception) {
            Log::error('MTN MoMo callback verification failed', [
                'reference' => $reference,
                'message' => $exception->getMessage(),
            ]);

            return response()->noContent(500);
        }

        return response()->noContent();
    }

    private function applyStatus(string $transactionId, string $reference, array $result): string
    {
        $providerStatus = strtoupper($result['status'] ?? 'PENDING');
        $orderStatus = match ($providerStatus) {
            'SUCCESSFUL' => 'paid',
            'FAILED', 'REJECTED', 'EXPIRED' => 'failed',
            default => 'pending',
        };

        DB::transaction(function () use ($transactionId, $reference, $orderStatus) {
            $orders = Order::where('transaction_id', $transactionId)->lockForUpdate()->get();
            if ($orders->isEmpty() || $orders->every(fn (Order $order) => $order->payment_status === 'paid')) {
                return;
            }

            Order::where('transaction_id', $transactionId)->update(['payment_status' => $orderStatus]);

            if ($orderStatus === 'paid' && !Transaction::where('reference_id', $reference)->exists()) {
                Transaction::create([
                    'user_id' => $orders->first()->user_id,
                    'amount' => $orders->sum('total_amount'),
                    'type' => 'payment',
                    'description' => "MTN MoMo payment for order {$transactionId}",
                    'reference_id' => $reference,
                ]);
            }
        });

        return $providerStatus;
    }
}
