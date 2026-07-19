<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EarningsController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        $commissionRate = 0.05; // 5% platform fee

        // 1. Cleared Earnings (Delivered)
        $grossRealized = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendorId)
            ->where('orders.delivery_status', 'delivered')
            ->sum(DB::raw('order_items.quantity * order_items.price_at_purchase'));

        $netRealized = $grossRealized * (1 - $commissionRate);

        // 2. Pending Payouts (Active Orders)
        $grossPending = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendorId)
            ->whereNotIn('orders.delivery_status', ['delivered', 'cancelled'])
            ->sum(DB::raw('order_items.quantity * order_items.price_at_purchase'));

        $netPending = $grossPending * (1 - $commissionRate);

        // 3. Items Sold Count
        $itemsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendorId)
            ->where('orders.delivery_status', 'delivered')
            ->sum('order_items.quantity');

        // 4. Transaction Ledger (Last 50)
        $history = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.vendor_id', $vendorId)
            ->select(
                'orders.created_at',
                'orders.id as order_id',
                'products.title',
                'orders.delivery_status'
            )
            ->selectRaw('(order_items.quantity * order_items.price_at_purchase) as gross_amount')
            ->orderBy('orders.created_at', 'desc')
            ->limit(50)
            ->get();

        // 5. Chart Data (Last 30 Days)
        $chartData = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendorId)
            ->where('orders.delivery_status', 'delivered')
            ->where('orders.created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.quantity * order_items.price_at_purchase) as gross')
            ->groupBy(DB::raw('DATE(orders.created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $daysMap = [];
        for ($i = 29; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $dayLabel = now()->subDays($i)->format('M d');
            $daysMap[$dateStr] = [
                'label' => $dayLabel,
                'net' => 0.0
            ];
        }

        foreach ($chartData as $d) {
            $dateStr = date('Y-m-d', strtotime($d->date));
            if (isset($daysMap[$dateStr])) {
                $daysMap[$dateStr]['net'] = (float)($d->gross * (1 - $commissionRate));
            }
        }

        $dates = array_column($daysMap, 'label');
        $earnings = array_column($daysMap, 'net');

        return view('vendor.earnings', compact(
            'netRealized', 'netPending', 'itemsSold', 'history', 'dates', 'earnings', 'commissionRate'
        ));
    }
}
