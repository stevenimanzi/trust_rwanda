<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    // 1. Promotions List / Marketing Hub
    public function index()
    {
        $vendorId = Auth::id();
        $products = Product::where('user_id', $vendorId)->get();

        return view('vendor.promotions', compact('products'));
    }

    // 2. Request Promotion/Flash Deal
    public function requestPromo(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'discount_percent' => 'required|integer|min:0|max:95'
        ]);

        $vendorId = Auth::id();
        $productId = $request->input('product_id');
        $discount = (int)$request->input('discount_percent');
        $isFlash = $request->has('is_flash_deal') ? 1 : 0;

        $product = Product::where('id', $productId)->where('user_id', $vendorId)->first();

        if ($product) {
            $product->update([
                'discount_percent' => $discount,
                'is_flash_deal' => $isFlash,
                'promo_status' => 'pending'
            ]);

            return redirect()->route('vendor.promotions')->with('msg', 'Campaign request submitted successfully! Awaiting Admin activation.');
        }

        return redirect()->route('vendor.promotions')->with('error', 'Product not found.');
    }
}
