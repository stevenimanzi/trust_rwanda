<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class PromoRequestController extends Controller
{
    /**
     * Display Promo requests queue list
     */
    public function index()
    {
        $requests = Product::select('products.*')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('products.promo_status', 'pending')
            ->select('products.*', 'users.shop_name', 'users.phone as vendor_phone')
            ->orderBy('products.updated_at', 'asc')
            ->paginate(20);

        return view('admin.promo_requests', compact('requests'));
    }

    /**
     * Approve promotion deal request
     */
    public function approve(Request $request)
    {
        $id = $request->input('id');
        $product = Product::findOrFail($id);

        $product->update([
            'promo_status' => 'active',
            'is_visible' => 1, // Automatically activate product visibility if approved
        ]);

        return redirect()->back()->with('success', "Success: Promo unit status updated to ACTIVE");
    }

    /**
     * Reject promotion deal request
     */
    public function reject(Request $request)
    {
        $id = $request->input('id');
        $product = Product::findOrFail($id);

        $product->update([
            'promo_status' => 'none',
        ]);

        return redirect()->back()->with('success', "Success: Promo request has been REJECTED");
    }
}
