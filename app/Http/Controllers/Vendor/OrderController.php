<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    // 1. List Vendor Orders
    public function index(Request $request)
    {
        $vendorId = Auth::id();
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('q', '');

        $query = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('products.user_id', $vendorId);

        if ($statusFilter !== 'all') {
            $query->where('orders.delivery_status', $statusFilter);
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('orders.id', 'like', '%' . $search . '%')
                  ->orWhere('users.full_name', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->select(
                'orders.id',
                'orders.created_at',
                'orders.delivery_status',
                'users.full_name',
                'users.phone'
            )
            ->selectRaw('SUM(order_items.quantity * order_items.price_at_purchase) as my_revenue')
            ->groupBy('orders.id', 'orders.created_at', 'orders.delivery_status', 'users.full_name', 'users.phone')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        // Calculate KPIs
        $kpiData = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $vendorId)
            ->select('orders.delivery_status')
            ->selectRaw('COUNT(DISTINCT orders.id) as count')
            ->groupBy('orders.delivery_status')
            ->get();

        $kpis = ['pending' => 0, 'confirmed' => 0, 'shipped' => 0, 'delivered' => 0, 'cancelled' => 0];
        foreach ($kpiData as $k) {
            if (array_key_exists($k->delivery_status, $kpis)) {
                $kpis[$k->delivery_status] = (int)$k->count;
            }
        }

        // AJAX response support
        if ($request->ajax() || $request->query('ajax') == 1) {
            $ordersAjax = [];
            foreach ($orders as $o) {
                $ordersAjax[] = [
                    'id' => $o->id,
                    'created_at' => $o->created_at,
                    'delivery_status' => $o->delivery_status,
                    'full_name' => $o->full_name,
                    'phone' => $o->phone,
                    'my_revenue' => $o->my_revenue,
                    'my_revenue_formatted' => number_format($o->my_revenue),
                    'id_padded' => str_pad($o->id, 5, '0', STR_PAD_LEFT),
                    'date_formatted' => date('M d, H:i', strtotime($o->created_at))
                ];
            }
            return response()->json([
                'orders' => $ordersAjax,
                'kpis' => $kpis
            ]);
        }

        return view('vendor.orders', compact('orders', 'statusFilter', 'search', 'kpis'));
    }

    // 2. View Order Details / Invoice
    public function details($id)
    {
        $vendorId = Auth::id();
        
        $order = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.id', $id)
            ->select('orders.*', 'users.full_name as customer_name', 'users.email as customer_email', 'users.phone as customer_phone')
            ->first();

        if (!$order) {
            abort(404, 'Order not found.');
        }

        $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.order_id', $id)
            ->where('order_items.vendor_id', $vendorId)
            ->select('order_items.*', 'products.title', 'products.image_url')
            ->get();

        $vendorSubtotal = 0;
        foreach ($items as $item) {
            $vendorSubtotal += ($item->quantity * $item->price_at_purchase);
        }

        return view('vendor.order_details', compact('order', 'items', 'vendorSubtotal'));
    }

    // 3. Update Order Status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled'
        ]);

        $newStatus = $request->input('status');
        $vendorId = Auth::id();
        $shopName = Auth::user()->shop_name ?? 'Vendor';

        // Check if this vendor owns at least one item in the order
        $hasItems = DB::table('order_items')
            ->where('order_id', $id)
            ->where('vendor_id', $vendorId)
            ->exists();

        if (!$hasItems) {
            abort(403, 'Unauthorized.');
        }

        try {
            DB::table('orders')->where('id', $id)->update([
                'delivery_status' => $newStatus
            ]);

            // Retrieve customer details
            $order = DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->where('orders.id', $id)
                ->select('orders.*', 'users.full_name as customer_name', 'users.email as customer_email')
                ->first();

            if ($order && !empty($order->customer_email)) {
                $subject = "Order #{$id} Update: " . strtoupper($newStatus);
                Mail::html("
                <div style='font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; border-radius: 15px; overflow: hidden;'>
                    <div style='background: #4f46e5; padding: 25px; text-align: center; color: white;'>
                        <h2 style='margin: 0;'>Order Update</h2>
                    </div>
                    <div style='padding: 30px; text-align: center;'>
                        <p>Hello <strong>{$order->customer_name}</strong>, your order <strong>#{$id}</strong> at <strong>{$shopName}</strong> has a new status:</p>
                        <div style='background: #f1f5f9; padding: 20px; border-radius: 10px; margin: 20px 0;'>
                            <span style='color: #4f46e5; font-size: 1.5rem; font-weight: bold;'>" . strtoupper($newStatus) . "</span>
                        </div>
                        <p style='color: #64748b;'>Login to your dashboard to see more details.</p>
                    </div>
                </div>", function ($message) use ($order, $subject) {
                    $message->to($order->customer_email)->subject($subject);
                });
            }
        } catch (\Exception $e) {
            // Log or ignore notifications fail so order flow continues
        }

        return redirect()->route('vendor.orders.details', $id)->with('msg', 'Status Synchronized');
    }
}
