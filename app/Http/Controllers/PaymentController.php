<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PesapalService;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $orderTrackingId = $request->input('OrderTrackingId');
        $merchantReference = $request->input('OrderMerchantReference');
        
        try {
            $pesapal = new PesapalService();
            $token = $pesapal->authenticate();
            $statusResponse = $pesapal->getTransactionStatus($token, $orderTrackingId);
            
            $status = $statusResponse['payment_status_description'] ?? 'PENDING';
            $merchantRef = $statusResponse['merchant_reference'] ?? $merchantReference;
            
            if (strtoupper($status) === 'COMPLETED') {
                $this->updateOrdersStatus($merchantRef, 'paid', $orderTrackingId);
                return redirect()->route('order.success')->with('message', 'Payment successful!');
            } elseif (strtoupper($status) === 'FAILED') {
                $this->updateOrdersStatus($merchantRef, 'failed', $orderTrackingId);
                return redirect()->route('cart.index')->with('error', 'Payment failed or was cancelled.');
            }
            
            return redirect()->route('order.success')->with('message', 'Payment is being processed. You will be notified once confirmed.');

        } catch (\Exception $e) {
            Log::error('Pesapal Callback Error: ' . $e->getMessage());
            return redirect()->route('products.index')->with('message', 'Order received, payment status is pending verification.');
        }
    }

    public function ipn(Request $request)
    {
        $orderTrackingId = $request->input('OrderTrackingId');
        $merchantReference = $request->input('OrderMerchantReference');

        Log::info('Pesapal IPN Received', $request->all());

        try {
            $pesapal = new PesapalService();
            $token = $pesapal->authenticate();
            $statusResponse = $pesapal->getTransactionStatus($token, $orderTrackingId);
            
            $status = $statusResponse['payment_status_description'] ?? 'PENDING';
            $merchantRef = $statusResponse['merchant_reference'] ?? $merchantReference;
            
            if (strtoupper($status) === 'COMPLETED') {
                $this->updateOrdersStatus($merchantRef, 'paid', $orderTrackingId);
            } elseif (strtoupper($status) === 'FAILED') {
                $this->updateOrdersStatus($merchantRef, 'failed', $orderTrackingId);
            }

            return response()->json([
                'orderNotificationType' => $request->input('OrderNotificationType'),
                'orderTrackingId' => $orderTrackingId,
                'orderMerchantReference' => $merchantReference,
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            Log::error('Pesapal IPN Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    protected function updateOrdersStatus($transactionId, $status, $trackingId)
    {
        if (!$transactionId) return;

        DB::transaction(function() use ($transactionId, $status, $trackingId) {
            Order::where('transaction_id', $transactionId)
                 ->where('payment_status', '!=', 'paid')
                 ->update([
                     'payment_status' => $status,
                     'payment_method' => 'pesapal'
                 ]);
                 
            // Insert into transactions table if it doesn't exist
            $exists = Transaction::where('reference_id', $trackingId)->first();
            if (!$exists && $status === 'paid') {
                $firstOrder = Order::where('transaction_id', $transactionId)->first();
                if ($firstOrder) {
                    $total = Order::where('transaction_id', $transactionId)->sum('total_amount');
                    Transaction::create([
                        'user_id' => $firstOrder->user_id,
                        'amount' => $total,
                        'type' => 'payment',
                        'description' => 'Pesapal Payment for Order ' . $transactionId,
                        'reference_id' => $trackingId
                    ]);
                }
            }
        });
    }
}
