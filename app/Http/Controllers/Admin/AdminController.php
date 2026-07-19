<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Dashboard View & Real-Time updates
     */
    public function dashboard(Request $request)
    {
        // Handle AJAX Real-Time Polling
        if ($request->ajax() || $request->query('ajax') == 1) {
            $chartLabels = [];
            $chartData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i)->format('Y-m');
                $monthName = now()->subMonths($i)->format('M');
                
                $total = Order::whereRaw("strftime('%Y-%m', created_at) = ?", [$date])
                    ->sum('total_amount');
                    
                $chartLabels[] = $monthName;
                $chartData[] = (float)$total;
            }
            $pendingVendors = User::where('role', 'vendor')
                ->where('is_verified', 0)
                ->limit(4)
                ->get();

            return response()->json([
                'chartLabels' => $chartLabels,
                'chartData' => $chartData,
                'pendingVendors' => $pendingVendors
            ]);
        }

        // Stats Aggregation
        $totalUsers = User::count();
        $totalVendors = User::where('role', 'vendor')->count();
        $totalOrders = Order::count();
        $revenue = Order::where('delivery_status', '!=', 'cancelled')->sum('total_amount');

        // Last 6 months Chart data
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i)->format('Y-m');
            $monthName = now()->subMonths($i)->format('M');
            
            $total = Order::whereRaw("strftime('%Y-%m', created_at) = ?", [$date])
                ->sum('total_amount');
                
            $chartLabels[] = $monthName;
            $chartData[] = (float)$total;
        }

        $recentOrders = Order::with('customer')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
            
        $pendingVendors = User::where('role', 'vendor')
            ->where('is_verified', 0)
            ->limit(4)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalVendors', 'totalOrders', 'revenue',
            'chartLabels', 'chartData', 'recentOrders', 'pendingVendors'
        ));
    }

    /**
     * Sales Reports View
     */
    public function reports(Request $request)
    {
        $range = $request->query('range', '30');
        $endDate = now()->format('Y-m-d H:i:s');
        
        if ($range === 'all') {
            $startDate = '2025-01-01 00:00:00';
        } else {
            $startDate = now()->subDays((int)$range)->format('Y-m-d 00:00:00');
        }

        // Daily trend data
        $trendData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $chartLabels = [];
        $chartRevenue = [];
        $totalRevenue = 0;
        $totalOrders = 0;

        foreach ($trendData as $day) {
            $chartLabels[] = date('M d', strtotime($day->date));
            $chartRevenue[] = (float)$day->revenue;
            $totalRevenue += $day->revenue;
            $totalOrders += $day->orders;
        }

        // Top Vendor sales
        $topVendors = DB::select("
            SELECT u.shop_name, SUM(oi.quantity * oi.price_at_purchase) as total_sales, COUNT(DISTINCT oi.order_id) as order_count
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id
            JOIN users u ON p.user_id = u.id 
            JOIN orders o ON oi.order_id = o.id
            WHERE o.created_at BETWEEN ? AND ? 
            GROUP BY p.user_id 
            ORDER BY total_sales DESC 
            LIMIT 5
        ", [$startDate, $endDate]);

        // Category breakdown
        $catData = DB::select("
            SELECT p.category, COUNT(*) as count 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id 
            JOIN orders o ON oi.order_id = o.id
            WHERE o.created_at BETWEEN ? AND ? 
            GROUP BY p.category
        ", [$startDate, $endDate]);

        $catLabels = [];
        $catCounts = [];
        foreach ($catData as $c) {
            $catLabels[] = ucfirst($c->category);
            $catCounts[] = $c->count;
        }

        return view('admin.reports', compact(
            'range', 'totalRevenue', 'totalOrders', 'chartLabels', 'chartRevenue',
            'topVendors', 'catLabels', 'catCounts'
        ));
    }

    /**
     * SMS History Logs View
     */
    public function smsLogs()
    {
        $logs = SmsLog::with('vendor')
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get();
            
        $failCount = SmsLog::where('status', 'failed')->count();

        return view('admin.sms_logs', compact('logs', 'failCount'));
    }

    /**
     * Broadcast SMS to all vendors
     */
    public function broadcastSms(Request $request)
    {
        $broadcastMsg = trim($request->input('broadcast_message'));
        if (empty($broadcastMsg)) {
            return redirect()->back()->with('error', 'Message cannot be empty.');
        }

        $vendors = User::where('role', 'vendor')->get();
        $successCount = 0;

        foreach ($vendors as $v) {
            $vPhone = preg_replace('/[^0-9]/', '', $v->phone);
            if (strlen($vPhone) == 9) $vPhone = '250' . $vPhone;
            if (strpos($vPhone, '0') === 0 && strlen($vPhone) == 10) $vPhone = '250' . substr($vPhone, 1);

            // Placeholder gateway response
            $status = 'sent';
            
            SmsLog::create([
                'order_id' => 0,
                'vendor_id' => $v->id,
                'recipient' => '+' . $vPhone,
                'message_body' => $broadcastMsg,
                'gateway_response' => 'Manual Broadcast Sent via Laravel Gateway',
                'status' => $status,
            ]);

            if ($status === 'sent') {
                $successCount++;
            }
        }

        return redirect()->back()->with('success', "System: {$successCount} messages broadcast successfully.");
    }
}
