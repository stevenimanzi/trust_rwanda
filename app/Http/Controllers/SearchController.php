<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Property;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('q', '');
        
        $products = collect();
        $properties = collect();

        if (!empty($query)) {
            // Search Products
            $products = Product::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('category', 'like', "%{$query}%")
                ->take(8)
                ->get();
                
            // Search Properties
            $properties = Property::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('address', 'like', "%{$query}%")
                ->orWhere('property_type', 'like', "%{$query}%")
                ->take(8)
                ->get();
        } else {
            // If no query, maybe just return latest of both
            $products = Product::latest()->take(10)->get();
            $properties = Property::latest()->take(10)->get();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'products' => $products,
                'properties' => $properties
            ]);
        }

        return view('store.search_results', compact('products', 'properties', 'query'));
    }
}
