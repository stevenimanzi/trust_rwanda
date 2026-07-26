<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MomoPaymentController extends Controller
{
    /**
     * Show the USSD Mobile Money pending popup.
     */
    public function pending(string $transactionId)
    {
        $orders = Order::where('transaction_id', $transactionId)->get();
        
        if ($orders->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Order not found.');
        }

        // Get total amount
        $totalAmount = $orders->sum('total_amount');
        
        // Pass the transaction ID and total amount to the view
        return view('store.momo_pending', compact('transactionId', 'totalAmount'));
    }

    /**
     * Check the status of the mobile money payment via AJAX.
     */
    public function status(string $transactionId)
    {
        $orders = Order::where('transaction_id', $transactionId)->get();
        
        if ($orders->isEmpty()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Order not found']);
        }
        
        $order = $orders->first();
        
        // --- SIMULATION LOGIC FOR LOCAL TESTING ---
        // If we are testing locally, simulate a successful USSD push approval after 10 seconds
        if (app()->isLocal()) {
            $timeSinceCreation = now()->diffInSeconds($order->created_at);
            
            if ($timeSinceCreation > 8) {
                // Simulate approval
                Order::where('transaction_id', $transactionId)->update(['payment_status' => 'PAID']);
                
                // Store in session to ensure order.success can display it
                session(['latest_order_secure' => $transactionId]);
                
                return response()->json(['status' => 'PAID']);
            }
            
            // Still waiting
            return response()->json(['status' => 'PENDING']);
        }
        // -------------------------------------------
        
        // In production, this would query the real MTN MoMo API or rely on the MoMo Callback (Webhook)
        // For now, it just returns whatever is in the database, waiting for the webhook to update it.
        return response()->json(['status' => strtoupper($order->payment_status)]);
    }
}
