<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\DB;

class FrontendPropertyController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $query = Property::with(['owner', 'images', 'features'])
            ->where('status', 'available');
            
        if ($category !== 'all' && $category !== 'real-estate') {
            // e.g. rent-house -> listing_type = rent, property_type = house
            if (str_starts_with($category, 'rent-')) {
                $query->where('listing_type', 'rent');
                $type = str_replace('rent-', '', $category);
                if ($type !== 'all') $query->where('property_type', $type);
            } elseif (str_starts_with($category, 'sale-')) {
                $query->where('listing_type', 'sale');
                $type = str_replace('sale-', '', $category);
                if ($type !== 'all') $query->where('property_type', $type);
            }
        }
        
        $search = $request->query('q', '');
        $sort = $request->query('sort', 'newest');
        
        // Intelligent Multi-Keyword Search
        if (!empty($search)) {
            $searchTerm = trim($search);
            $keywords = array_slice(array_filter(explode(' ', $searchTerm)), 0, 5);
            
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $searchLike = '%' . $keyword . '%';
                    $q->where(function($subQ) use ($searchLike) {
                        $subQ->where('title', 'LIKE', $searchLike)
                             ->orWhere('description', 'LIKE', $searchLike)
                             ->orWhere('address', 'LIKE', $searchLike)
                             ->orWhere('district', 'LIKE', $searchLike)
                             ->orWhere('sector', 'LIKE', $searchLike);
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
        
        $properties = $query->paginate(12)->withQueryString();
        
        return view('store.properties_index', compact('properties', 'category', 'search', 'sort'));
    }

    public function show($id)
    {
        $property = Property::with(['owner', 'images', 'features'])
            ->where('id', $id)
            ->where('status', 'available')
            ->firstOrFail();

        // Increment Views Count (if we add a views_count to properties in the future, for now just dummy or basic tracking)
        // Let's assume properties don't have views_count yet, or we can just ignore it for now.

        // Get related properties (same property_type)
        $related = Property::with(['images', 'features'])
            ->where('property_type', $property->property_type)
            ->where('id', '!=', $id)
            ->where('status', 'available')
            ->take(4)
            ->get();

        return view('store.property_details', compact('property', 'related'));
    }
}
