<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $isLoggedIn = auth()->check();
        $userId = $isLoggedIn ? auth()->id() : null;
        $refLink = $isLoggedIn ? route('home', ['ref' => $userId]) : '';

        // Default stats
        $affStats = [
            'total_referrals' => 0,
            'pending_comm' => 0,
            'approved_comm' => 0,
            'paid_comm' => 0
        ];
        $commissionsList = [];
        $allProducts = [];

        try {
            // Fetch all visible products for sharing
            $allProducts = Product::with('vendor')
                ->where('is_visible', 1)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($isLoggedIn) {
                // Fetch affiliate statistics
                $stats = AffiliateCommission::selectRaw("
                    COUNT(DISTINCT buyer_id) as total_referrals,
                    SUM(CASE WHEN status = 'pending' THEN commission_amount ELSE 0 END) as pending_comm,
                    SUM(CASE WHEN status = 'approved' THEN commission_amount ELSE 0 END) as approved_comm,
                    SUM(CASE WHEN status = 'paid' THEN commission_amount ELSE 0 END) as paid_comm
                ")
                ->where('referrer_id', $userId)
                ->first();

                if ($stats) {
                    $affStats = [
                        'total_referrals' => (int) $stats->total_referrals,
                        'pending_comm' => (float) ($stats->pending_comm ?? 0),
                        'approved_comm' => (float) ($stats->approved_comm ?? 0),
                        'paid_comm' => (float) ($stats->paid_comm ?? 0)
                    ];
                }

                // Fetch recent commissions list
                $commissionsList = AffiliateCommission::with(['buyer', 'product'])
                    ->where('referrer_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();
            }
        } catch (\Exception $e) {
            // Fallbacks in case tables/models are not fully migrated
        }

        $activeTab = $request->query('tab', 'dashboard');
        if (!in_array($activeTab, ['dashboard', 'products', 'how', 'tools'])) {
            $activeTab = 'dashboard';
        }

        $currentLang = app()->getLocale();

        return view('store.affiliate', compact(
            'isLoggedIn', 'userId', 'refLink', 'affStats', 'commissionsList', 'allProducts', 'activeTab', 'currentLang'
        ));
    }
}
