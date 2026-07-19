<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $now = now()->toDateTimeString();
        
        // Fetch Promotional Ads
        try {
            $bannerTopAds = DB::table('active_ads')
                ->where('placement', 'banner_top')
                ->where('status', 'active')
                ->where(function($query) use ($now) {
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function($query) use ($now) {
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->orderBy('priority', 'desc')
                ->take(3)
                ->get();

            $bannerMiddleAds = DB::table('active_ads')
                ->where('placement', 'banner_middle')
                ->where('status', 'active')
                ->where(function($query) use ($now) {
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function($query) use ($now) {
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->orderBy('priority', 'desc')
                ->take(3)
                ->get();

            $bannerBottomAds = DB::table('active_ads')
                ->where('placement', 'banner_bottom')
                ->where('status', 'active')
                ->where(function($query) use ($now) {
                    $query->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })
                ->where(function($query) use ($now) {
                    $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
                })
                ->orderBy('priority', 'desc')
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            $bannerTopAds = collect();
            $bannerMiddleAds = collect();
            $bannerBottomAds = collect();
        }

        // Fetch Products & Arrivals
        $featured = Product::with('vendor')
            ->where('is_visible', 1)
            ->inRandomOrder()
            ->take(8)
            ->get();

        $newArrivals = Product::with('vendor')
            ->where('is_visible', 1)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Marketplace Stats
        $totalProductsCount = Product::where('is_visible', 1)->count();
        $totalVendorsCount = User::where('role', 'vendor')->count();
        
        $happyClientsCount = 0;
        try {
            $happyClientsCount = DB::table('orders')
                ->where('delivery_status', 'delivered')
                ->distinct('user_id')
                ->count('user_id');
        } catch (\Exception $e) {}

        $displayProducts = $totalProductsCount + 100;
        $displayVendors = $totalVendorsCount;
        $displayClients = $happyClientsCount + 50;

        // Trending & Top Sellers
        $trending = Product::with('vendor')
            ->where('is_visible', 1)
            ->orderBy('views_count', 'desc')
            ->take(12)
            ->get();

        $topSellers = User::select('users.id', 'users.shop_name', 'users.shop_logo', 'users.phone')
            ->join('products', 'products.user_id', '=', 'users.id')
            ->where('products.is_visible', 1)
            ->where('users.role', 'vendor')
            ->selectRaw('COUNT(products.id) as product_count')
            ->groupBy('users.id', 'users.shop_name', 'users.shop_logo', 'users.phone')
            ->orderBy('product_count', 'desc')
            ->take(6)
            ->get();

        // Categories Nodes
        $electronics = Product::with('vendor')
            ->where('category', 'electronics')
            ->where('is_visible', 1)
            ->take(4)
            ->get();

        $foodItems = Product::with('vendor')
            ->whereIn('category', ['food', 'vegetables', 'farmers-market'])
            ->where('is_visible', 1)
            ->take(4)
            ->get();

        $building = Product::with('vendor')
            ->where('category', 'building')
            ->where('is_visible', 1)
            ->take(4)
            ->get();

        $fashion = Product::with('vendor')
            ->where('category', 'fashion')
            ->where('is_visible', 1)
            ->take(4)
            ->get();

        // Active Campaign Nodes
        $activeFlash = Product::with('vendor')
            ->where('is_flash_deal', 1)
            ->where('is_visible', 1)
            ->take(10)
            ->get();

        // Real Estate Showcase (from properties table)
        $realEstateListings = \App\Models\Property::with(['owner', 'images', 'features'])
            ->where('status', 'available')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        // Paid Hero Promo
        $paidHeroProducts = Product::with('vendor')
            ->where('is_visible', 1)
            ->where('promo_status', 'active')
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        if ($paidHeroProducts->isEmpty()) {
            $paidHeroProducts = $featured->take(4);
        }

        // Reviews & Ratings
        $homeReviews = collect();
        $homeReviewCount = 0;
        $homeAvgRating = 0.0;
        try {
            $homeReviews = DB::table('reviews')
                ->join('users', 'users.id', '=', 'reviews.user_id')
                ->join('products', 'products.id', '=', 'reviews.product_id')
                ->select('reviews.rating', 'reviews.review_text', 'reviews.created_at', 'users.full_name', 'products.title as product_title')
                ->orderBy('reviews.created_at', 'desc')
                ->take(6)
                ->get();

            $summary = DB::table('reviews')
                ->selectRaw('COUNT(*) as total_reviews, ROUND(AVG(rating), 1) as avg_rating')
                ->first();
            
            if ($summary) {
                $homeReviewCount = (int) $summary->total_reviews;
                $homeAvgRating = (float) $summary->avg_rating;
            }
        } catch (\Exception $e) {}

        return view('store.home', compact(
            'bannerTopAds', 'bannerMiddleAds', 'bannerBottomAds',
            'featured', 'newArrivals',
            'displayProducts', 'displayVendors', 'displayClients',
            'trending', 'topSellers',
            'electronics', 'foodItems', 'building', 'fashion',
            'activeFlash', 'realEstateListings', 'paidHeroProducts',
            'homeReviews', 'homeReviewCount', 'homeAvgRating'
        ));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'subscribe_email' => 'required|email'
        ]);

        $email = $request->input('subscribe_email');

        try {
            $exists = DB::table('newsletter_subscribers')->where('email', $email)->exists();
            if (!$exists) {
                DB::table('newsletter_subscribers')->insert([
                    'email' => $email,
                    'created_at' => now()
                ]);
                return back()->with('newsletter_msg', '<i class="bi bi-check-circle-fill me-1"></i> Subscribed successfully!');
            }
            return back()->with('newsletter_msg', '<i class="bi bi-info-circle-fill me-1"></i> Already subscribed!');
        } catch (\Exception $e) {
            return back()->with('newsletter_msg', '<i class="bi bi-exclamation-triangle-fill me-1"></i> Database error.');
        }
    }
}
