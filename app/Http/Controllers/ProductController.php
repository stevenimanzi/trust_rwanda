<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        
        // Redirect legacy real estate queries to the new properties system
        if (in_array($category, ['real-estate', 'rent-house', 'rent-apartment', 'rent-guest-house', 'rent-ghetto', 'sale-land', 'sale-house'])) {
            return redirect()->route('properties.index', ['category' => $category === 'real-estate' ? 'all' : $category]);
        }
        
        $search = $request->query('q', '');
        $sort = $request->query('sort', 'newest');

        if ($category === 'real-estate' || $category === 'real_estate') {
            $properties = \App\Models\Property::with(['owner', 'features', 'images'])
                ->where(function($q) {
                    $q->where('status', 'available')
                      ->orWhere('status', 'pending')
                      ->orWhere('status', '')
                      ->orWhereNull('status');
                })
                ->orderBy('created_at', 'DESC')
                ->get();

            $propertiesList = [];
            foreach ($properties as $p) {
                $beds = 0;
                $baths = 0;
                $size = 0;
                foreach ($p->features as $f) {
                    $fname = strtolower($f->feature_name);
                    $fval = (int)$f->feature_value;
                    if (strpos($fname, 'bedroom') !== false || strpos($fname, 'bed') !== false) {
                        $beds = $fval;
                    } elseif (strpos($fname, 'bathroom') !== false || strpos($fname, 'bath') !== false || strpos($fname, 'toilet') !== false) {
                        $baths = $fval;
                    } elseif (strpos($fname, 'size') !== false || strpos($fname, 'area') !== false || strpos($fname, 'sqm') !== false) {
                        $size = $fval;
                    }
                }

                $imageUrl = $p->images->sortBy('sort_order')->first()?->image_url ?? 'https://placehold.co/600x400?text=Property';

                $propertiesList[] = [
                    'id' => (int)$p->id,
                    'title' => $p->title,
                    'description' => $p->description ?? '',
                    'price' => (float)$p->price,
                    'category' => $p->property_type,
                    'listing_type' => $p->listing_type,
                    'price_period' => $p->price_period,
                    'beds' => $beds,
                    'baths' => $baths,
                    'size_sqm' => $size ?: 150,
                    'address' => ($p->sector ? $p->sector . ', ' : '') . ($p->district ?: 'Kigali, Rwanda'),
                    'latitude' => (float)($p->latitude ?: -1.9441),
                    'longitude' => (float)($p->longitude ?: 30.0619),
                    'image_url' => kura_product_image_url($imageUrl, 'https://placehold.co/600x400?text=Property'),
                    'shop_name' => $p->owner->shop_name ?? 'Verified Owner',
                    'phone' => $p->owner->phone ?? '+250788300300',
                    'has_video' => !empty($p->youtube_video_id)
                ];
            }

            return view('store.real_estate', [
                'propertiesList' => $propertiesList,
                'lat' => $request->query('lat', -1.9441),
                'lng' => $request->query('lng', 30.0619),
            ]);
        }

        // Fetch categories list
        $categoryList = Category::all()->toArray();
        $categoryMap = [];
        foreach ($categoryList as $catNode) {
            $categoryMap[$catNode['slug']] = $catNode;
        }

        $isElectronicsPage = ($category === 'electronics');
        $isSecondHandPage = ($category === 'second-hand');
        $electronicsSubCats = [];
        $secondHandSubCats = [];
        $selectedSubCat = 'all';

        if ($isElectronicsPage) {
            $electronicsSubCats = array_filter($categoryList, function($c) {
                return $c['type'] === 'electronics-sub';
            });
            $selectedSubCat = $request->query('sub', 'all');
        } elseif ($isSecondHandPage) {
            $secondHandSubCats = array_filter($categoryList, function($c) {
                return $c['type'] === 'second-hand-sub';
            });
            $selectedSubCat = $request->query('sub', 'all');
        }

        // Build query
        $query = Product::with('vendor')
            ->where('is_visible', 1);

        // Apply category filter
        if ($isElectronicsPage) {
            if ($selectedSubCat !== 'all') {
                $query->where('category', $selectedSubCat);
            } else {
                $elecSlugs = array_column($electronicsSubCats, 'slug');
                $elecSlugs[] = 'electronics';
                $query->whereIn('category', $elecSlugs);
            }
        } elseif ($isSecondHandPage) {
            if ($selectedSubCat !== 'all') {
                $query->where('category', $selectedSubCat);
            } else {
                $shSlugs = array_column($secondHandSubCats, 'slug');
                $shSlugs[] = 'second-hand';
                $shSlugs[] = 'second_hand';
                $query->whereIn('category', $shSlugs);
            }
        } elseif ($category !== 'all') {
            $query->where('category', $category);
        }

        // Apply Search Filter (Intelligent Multi-Keyword)
        if (!empty($search)) {
            $searchTerm = trim($search);
            // Split by spaces, limit to 5 keywords to prevent abuse
            $keywords = array_slice(array_filter(explode(' ', $searchTerm)), 0, 5);
            
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $searchLike = '%' . $keyword . '%';
                    
                    $q->where(function($subQ) use ($searchLike) {
                        $subQ->where('title', 'LIKE', $searchLike)
                             ->orWhere('description', 'LIKE', $searchLike)
                             ->orWhere('category', 'LIKE', $searchLike)
                             ->orWhereExists(function($catQuery) use ($searchLike) {
                                 $catQuery->select(DB::raw(1))
                                          ->from('categories')
                                          ->whereColumn('categories.slug', 'products.category')
                                          ->where('categories.name', 'LIKE', $searchLike);
                             });
                    });
                }
            });
        }

        // Apply Sorting
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'ASC');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'DESC');
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        $products = $query->paginate(12)->withQueryString();

        // Page title resolution
        $pageTitle = __('all_products');
        if ($isElectronicsPage) {
            $pageTitle = __('electronics');
        } elseif ($isSecondHandPage) {
            $pageTitle = __('second_hand') !== 'second_hand' ? __('second_hand') : 'Second Hand';
        } elseif (!empty($search)) {
            $pageTitle = __('search_results');
        } elseif ($category !== 'all') {
            $pageTitle = $categoryMap[$category]['name'] ?? ucfirst($category);
        }

        return view('store.products', compact(
            'products', 'category', 'search', 'sort', 'pageTitle',
            'isElectronicsPage', 'isSecondHandPage', 'electronicsSubCats', 'secondHandSubCats', 'selectedSubCat', 'categoryList'
        ));
    }

    public function show($id)
    {
        $product = Product::with('vendor')
            ->where('id', $id)
            ->where('is_visible', 1)
            ->firstOrFail();

        // Increment Views Count
        $viewed = session('viewed_products', []);
        if (!in_array($id, $viewed)) {
            try {
                $product->increment('views_count');
                $viewed[] = $id;
                session(['viewed_products' => $viewed]);
            } catch (\Exception $e) {}
        }

        $avgRating = 0;
        $reviewCount = 0;
        $reviews = collect();
        try {
            $reviews = DB::table('reviews')
                ->join('users', 'users.id', '=', 'reviews.user_id')
                ->where('reviews.product_id', $id)
                ->select('reviews.rating', 'reviews.review_text', 'reviews.created_at', 'users.full_name')
                ->orderBy('reviews.created_at', 'desc')
                ->take(20)
                ->get();

            $stats = DB::table('reviews')
                ->where('product_id', $id)
                ->selectRaw('COUNT(*) as total_reviews, ROUND(AVG(rating), 1) as avg_rating')
                ->first();
            if ($stats) {
                $reviewCount = $stats->total_reviews;
                $avgRating = $stats->avg_rating ?? 0;
            }
        } catch (\Exception $e) {}

        $related = Product::where('category', $product->category)
            ->where('id', '!=', $id)
            ->where('is_visible', 1)
            ->take(4)
            ->get();

        $hasPurchased = false;
        if (auth()->check()) {
            $hasPurchased = DB::table('orders')
                ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.user_id', auth()->id())
                ->where('order_items.product_id', $id)
                ->where('orders.delivery_status', 'delivered')
                ->exists();
        }

        return view('store.product_details', compact('product', 'reviews', 'reviewCount', 'avgRating', 'related', 'hasPurchased'));
    }

    public function storeReview($id, Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1200'
        ]);

        if (!auth()->check()) {
            return back()->with('review_error', 'You must be logged in to leave a review.');
        }

        $hasPurchased = DB::table('orders')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.user_id', auth()->id())
            ->where('order_items.product_id', $id)
            ->where('orders.delivery_status', 'delivered')
            ->exists();

        if (!$hasPurchased) {
            return back()->with('review_error', 'You can only review products you have purchased and received.');
        }

        try {
            $existing = DB::table('reviews')
                ->where('product_id', $id)
                ->where('user_id', auth()->id())
                ->first();

            if ($existing) {
                DB::table('reviews')
                    ->where('id', $existing->id)
                    ->update([
                        'rating' => $request->input('rating'),
                        'review_text' => $request->input('review_text'),
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('reviews')->insert([
                    'product_id' => $id,
                    'user_id' => auth()->id(),
                    'rating' => $request->input('rating'),
                    'review_text' => $request->input('review_text'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return back()->with('review_success', 'Thank you! Your review has been saved.');
        } catch (\Exception $e) {
            return back()->with('review_error', 'Database error saving review.');
        }
    }

    public function farmersMarket(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = $request->query('q', '');
        $sort = $request->query('sort', 'newest');

        $query = Product::with('vendor')
            ->where('is_visible', 1)
            ->where('is_fresh_produce', 1);

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        if (!empty($search)) {
            $searchTerm = trim($search);
            $keywords = array_slice(array_filter(explode(' ', $searchTerm)), 0, 5);
            
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $searchLike = '%' . $keyword . '%';
                    
                    $q->where(function($subQ) use ($searchLike) {
                        $subQ->where('title', 'LIKE', $searchLike)
                             ->orWhere('description', 'LIKE', $searchLike)
                             ->orWhere('category', 'LIKE', $searchLike);
                    });
                }
            });
        }

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'ASC');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'DESC');
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('store.farmers_marketplace', compact('products', 'category', 'search', 'sort'));
    }

    public function nearbyShops(Request $request)
    {
        $userLat = $request->query('lat') ? (float)$request->query('lat') : -1.9441;
        $userLng = $request->query('lng') ? (float)$request->query('lng') : 30.0619;
        $selectedCat = $request->query('category', 'all');
        $radius = 15;

        $nearbyShops = User::select('id', 'shop_name', 'address', 'shop_logo', 'latitude', 'longitude', 'shop_description', 'is_verified')
            ->selectRaw('(6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * sin(radians(latitude))
            )) AS distance', [$userLat, $userLng, $userLat])
            ->where('role', 'vendor')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($selectedCat !== 'all') {
            $nearbyShops->whereIn('id', function($query) use ($selectedCat) {
                $query->select('user_id')->from('products')->where('category', $selectedCat)->distinct();
            });
        }

        try {
            $shops = $nearbyShops->having('distance', '<=', $radius)
                ->orderBy('distance', 'ASC')
                ->get();
        } catch (\Exception $e) {
            $shops = collect();
        }

        // Fetch active categories
        $activeCategories = [];
        try {
            $existingCatSlugs = Product::distinct()->pluck('category')->toArray();
            $allCategories = Category::all()->toArray();
            $activeCategories = array_filter($allCategories, function($c) use ($existingCatSlugs) {
                return in_array($c['slug'], $existingCatSlugs);
            });
        } catch (\Exception $e) {}

        return view('store.nearby_shops', [
            'nearbyShops' => $shops,
            'userLat' => $userLat,
            'userLng' => $userLng,
            'selectedCat' => $selectedCat,
            'activeCategories' => $activeCategories
        ]);
    }
}
