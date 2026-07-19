<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\AffiliateCommission;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show user profile details and affiliate statistics.
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Fetch recent orders (transaction history)
        $orders = Order::where('user_id', $userId)
                       ->orderBy('created_at', 'DESC')
                       ->limit(5)
                       ->get();

        // Calculate total spent
        $totalSpent = Order::where('user_id', $userId)->sum('total_amount');

        // Fetch affiliate statistics
        $totalReferrals = AffiliateCommission::where('referrer_id', $userId)
                                            ->distinct('buyer_id')
                                            ->count('buyer_id');

        $pendingComm = AffiliateCommission::where('referrer_id', $userId)
                                          ->where('status', 'pending')
                                          ->sum('commission_amount');

        $approvedComm = AffiliateCommission::where('referrer_id', $userId)
                                           ->where('status', 'approved')
                                           ->sum('commission_amount');

        $paidComm = AffiliateCommission::where('referrer_id', $userId)
                                       ->where('status', 'paid')
                                       ->sum('commission_amount');

        // Commissions Log
        $commissionsList = AffiliateCommission::with(['buyer', 'product'])
                                               ->where('referrer_id', $userId)
                                               ->orderBy('created_at', 'DESC')
                                               ->limit(10)
                                               ->get();

        // Unique referral link
        $refLink = url('/?ref=' . $userId);

        return view('profile.index', compact(
            'user',
            'orders',
            'totalSpent',
            'totalReferrals',
            'pendingComm',
            'approvedComm',
            'paidComm',
            'commissionsList',
            'refLink'
        ));
    }

    /**
     * Update user account information.
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:120',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'address' => 'nullable|string|max:220',
        ]);

        $user->update([
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);

        return back()->with('status', 'Profile details updated successfully.');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return back()->with('status', 'Password changed successfully.');
    }
}
