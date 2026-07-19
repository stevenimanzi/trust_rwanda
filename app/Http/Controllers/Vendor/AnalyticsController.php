<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $vendorId = Auth::id();
        
        // 1. KPI Metrics
        $revenueData = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendorId)
            ->where('orders.payment_status', 'paid')
            ->selectRaw('SUM(order_items.quantity * order_items.price_at_purchase) as total')
            ->first();
        
        $totalSales = $revenueData->total ?? 0;
        
        $orderCount = DB::table('order_items')
            ->where('vendor_id', $vendorId)
            ->distinct('order_id')
            ->count('order_id');
            
        $productCount = Product::where('user_id', $vendorId)->count();

        // 2. Trend line chart (paid orders only, last 7 days)
        $chartData = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.vendor_id', $vendorId)
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.quantity * order_items.price_at_purchase) as daily_total')
            ->groupBy(DB::raw('DATE(orders.created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $daysMap = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $dayLabel = now()->subDays($i)->format('D, M j');
            $daysMap[$dateStr] = [
                'label' => $dayLabel,
                'total' => 0.0
            ];
        }

        foreach ($chartData as $row) {
            $dateStr = date('Y-m-d', strtotime($row->date));
            if (isset($daysMap[$dateStr])) {
                $daysMap[$dateStr]['total'] = (float)$row->daily_total;
            }
        }

        $dates = array_column($daysMap, 'label');
        $totals = array_column($daysMap, 'total');

        // SVG curve coordinates for Line Chart
        $maxSale = !empty($totals) ? max($totals) : 0;
        if ($maxSale <= 0) $maxSale = 10000;

        $points = [];
        $xStep = 110;
        $startX = 60;
        for ($i = 0; $i < 7; $i++) {
            $val = $totals[$i] ?? 0;
            $x = $startX + ($i * $xStep);
            $y = 250 - (($val / $maxSale) * 200);
            $points[] = ['x' => $x, 'y' => $y, 'val' => $val];
        }

        $pathD = "M " . $points[0]['x'] . " " . $points[0]['y'];
        for ($i = 1; $i < 7; $i++) {
            $prev = $points[$i - 1];
            $curr = $points[$i];
            $cp1x = $prev['x'] + ($xStep / 2);
            $cp1y = $prev['y'];
            $cp2x = $curr['x'] - ($xStep / 2);
            $cp2y = $curr['y'];
            $pathD .= " C $cp1x $cp1y, $cp2x $cp2y, " . $curr['x'] . " " . $curr['y'];
        }
        $areaD = $pathD . " L " . $points[6]['x'] . " 250 L " . $points[0]['x'] . " 250 Z";

        // 3. Top Products (Sales volume & revenue)
        $topProducts = DB::table('order_items')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.vendor_id', $vendorId)
            ->select('products.id', 'products.title', 'products.image_url')
            ->selectRaw('SUM(order_items.quantity) as sales_count, SUM(order_items.quantity * order_items.price_at_purchase) as revenue')
            ->groupBy('products.id', 'products.title', 'products.image_url')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        $totalSalesCount = 0;
        foreach ($topProducts as $tp) {
            $totalSalesCount += $tp->sales_count;
        }

        // SVG Doughnut segment calculation
        // Circumference = 2 * PI * r = 2 * 3.14159 * 15.915 = 100
        $doughnutSegments = [];
        $currentOffset = 0;
        $colors = ['#4F46E5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
        
        foreach ($topProducts as $index => $tp) {
            $percent = $totalSalesCount > 0 ? ($tp->sales_count / $totalSalesCount) * 100 : 0;
            $title = $tp->title ?? 'Item #' . $tp->id;
            
            $doughnutSegments[] = [
                'title' => $title,
                'sales_count' => $tp->sales_count,
                'revenue' => $tp->revenue,
                'percent' => round($percent, 1),
                'dasharray' => $percent . ' ' . (100 - $percent),
                'dashoffset' => 100 - $currentOffset + 25, // offset by 25 to start at 12 o'clock
                'color' => $colors[$index % count($colors)]
            ];
            $currentOffset += $percent;
        }

        if ($request->ajax() || $request->query('ajax') == 1) {
            return response()->json([
                'kpis' => [
                    'totalSales' => number_format((float)$totalSales),
                    'orderCount' => $orderCount,
                    'productCount' => $productCount
                ],
                'chart1' => [
                    'labels' => $dates,
                    'data' => $totals
                ],
                'chart2' => [
                    'labels' => array_column($doughnutSegments, 'title'),
                    'data' => array_column($doughnutSegments, 'sales_count')
                ],
                'topProducts' => $topProducts
            ]);
        }

        return view('vendor.analytics', compact(
            'totalSales', 'orderCount', 'productCount', 'dates', 'totals',
            'points', 'pathD', 'areaD', 'topProducts', 'doughnutSegments', 'totalSalesCount'
        ));
    }
}
