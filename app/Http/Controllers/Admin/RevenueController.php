<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    /**
     * Display revenue analytics screen
     */
    public function index(Request $request)
    {
        $commissionRate = 0.05; 
        $range = $request->query('range', '30');
        $endDate = now()->format('Y-m-d');
        
        if ($range === 'all') {
            $startDate = '2025-01-01';
        } else {
            $startDate = now()->subDays((int)$range)->format('Y-m-d');
        }

        $subIncome = Subscription::whereBetween('start_date', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->sum('amount');

        $gmv = Order::whereBetween('created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->where('delivery_status', '!=', 'cancelled')
            ->sum('total_amount');

        $commIncome = $gmv * $commissionRate;
        $netProfit = $subIncome + $commIncome;

        // Fetch subscriptions log
        $subs = DB::select("
            SELECT s.start_date as date, s.amount, u.shop_name as source, 'Subscription' as type 
            FROM subscriptions s 
            JOIN users u ON s.user_id = u.id 
            WHERE s.start_date BETWEEN ? AND ? 
            ORDER BY s.start_date DESC 
            LIMIT 25
        ", ["{$startDate} 00:00:00", "{$endDate} 23:59:59"]);

        // Fetch commission orders log
        $orders = DB::select("
            SELECT o.created_at as date, o.total_amount, u.full_name as source, 'Commission' as type 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.created_at BETWEEN ? AND ? 
              AND o.delivery_status != 'cancelled' 
            ORDER BY o.created_at DESC 
            LIMIT 25
        ", ["{$startDate} 00:00:00", "{$endDate} 23:59:59"]);

        $ledger = [];
        foreach ($subs as $s) {
            $ledger[] = [
                'date' => $s->date,
                'amount' => (float)$s->amount,
                'source' => $s->source,
                'type' => $s->type,
            ];
        }

        foreach ($orders as $o) {
            $ledger[] = [
                'date' => $o->date,
                'amount' => (float)$o->total_amount * $commissionRate,
                'source' => $o->source,
                'type' => $o->type,
            ];
        }

        // Sort ledger by date descending
        usort($ledger, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        // Limit to 25 items
        $ledger = array_slice($ledger, 0, 25);

        return view('admin.revenue', compact(
            'range', 'subIncome', 'gmv', 'commIncome', 
            'netProfit', 'ledger', 'commissionRate'
        ));
    }
}
