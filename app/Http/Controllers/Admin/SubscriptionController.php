<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display subscriptions control panel
     */
    public function index(Request $request)
    {
        $search = $request->query('q', '');
        
        $query = Subscription::select('subscriptions.*')
            ->join('users', 'subscriptions.user_id', '=', 'users.id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.shop_name', 'like', "%{$search}%")
                    ->orWhere('users.full_name', 'like', "%{$search}%");
            });
        }

        $subs = $query->orderBy('subscriptions.created_at', 'desc')
            ->select('subscriptions.*', 'users.full_name', 'users.shop_name', 'users.email')
            ->get();

        $vendors = User::where('role', 'vendor')->get();

        // Calculate metrics
        $totalRev = 0;
        $activeNow = 0;
        $today = now();

        foreach ($subs as $s) {
            $totalRev += (float)$s->amount;
            if ($s->end_date > $today && $s->status === 'active') {
                $activeNow++;
            }
        }

        return view('admin.subscriptions', compact('subs', 'vendors', 'search', 'totalRev', 'activeNow'));
    }

    /**
     * Store new subscription deployment
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_type' => 'required|in:2000,5000,10000',
            'duration' => 'required|in:1,6,12',
        ]);

        $userId = $request->input('user_id');
        $planType = $request->input('plan_type');
        $duration = (int)$request->input('duration');
        
        $endDate = now()->addMonths($duration)->format('Y-m-d H:i:s');
        $pName = [
            '2000' => 'Standard',
            '5000' => 'Professional',
            '10000' => 'Enterprise'
        ][$planType] ?? 'Custom';

        $user = User::findOrFail($userId);

        Subscription::create([
            'user_id' => $userId,
            'plan_name' => $pName,
            'amount' => $planType * $duration,
            'start_date' => now(),
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        $user->update([
            'subscription_status' => 'active',
            'subscription_expires_at' => $endDate,
            'is_verified' => 1,
        ]);

        return redirect()->back()->with('success', "NODE ACTIVATED: {$pName} cluster deployed for {$duration} months.");
    }

    /**
     * Revoke vendor access / subscription
     */
    public function revoke(Request $request)
    {
        $subId = $request->input('sub_id');
        $sub = Subscription::findOrFail($subId);

        $sub->update(['status' => 'cancelled']);
        
        $user = User::findOrFail($sub->user_id);
        $user->update(['subscription_status' => 'expired']);

        return redirect()->back()->with('success', "ACCESS TERMINATED: Vendor node disconnected.");
    }
}
