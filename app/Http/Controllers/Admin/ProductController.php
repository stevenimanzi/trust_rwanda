<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display listing of products
     */
    public function index(Request $request)
    {
        $catFilter = $request->query('category', 'all');
        $search = $request->query('q', '');

        $query = Product::query()->with('products'); // in user model products relation, wait, the Product model belongsTo a vendor, let's verify what the vendor relation is inside Product model.

        // Wait! Let's check the Product model to see how it belongs to a user (vendor)
        // Let's check relations of Product: user_id is the foreign key.
        // Let's look at Product.php later if needed, but normally it belongsTo User::class. Let's look at it.
        
        $query = Product::select('products.*')
            ->join('users', 'products.user_id', '=', 'users.id');

        if ($catFilter !== 'all') {
            $query->where('products.category', $catFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('products.title', 'like', "%{$search}%")
                    ->orWhere('users.shop_name', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('products.created_at', 'desc')
            ->select('products.*', 'users.shop_name', 'users.full_name as vendor_name')
            ->get();

        $categories = \App\Models\Category::orderBy('name', 'asc')->get();

        // Stats
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_visible', 1)->count();
        $lowStock = Product::where('stock_quantity', '<', 5)->where('stock_quantity', '>', 0)->count();
        $outOfStock = Product::where('stock_quantity', '<=', 0)->count();

        return view('admin.products', compact(
            'products', 'categories', 'catFilter', 'search',
            'totalProducts', 'activeProducts', 'lowStock', 'outOfStock'
        ));
    }

    /**
     * Toggle product visibility status
     */
    public function toggleStatus(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));
        $newStatus = (int)$request->input('new_status');

        $product->update(['is_visible' => $newStatus]);

        $msg = $newStatus ? "PRODUCT DEPLOYED TO FRONTEND" : "PRODUCT NODE DEACTIVATED";
        return redirect()->back()->with('success', $msg);
    }

    /**
     * Delete product SKU from catalog
     */
    public function delete(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));

        try {
            $product->delete();
            return redirect()->back()->with('success', "SKU PERMANENTLY PURGED");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "INTEGRITY ERROR: ACTIVE LINKAGES DETECTED");
        }
    }
}
