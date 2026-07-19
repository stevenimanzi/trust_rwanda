<?php

namespace App\Http\Controllers\PropertyOwner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $filterType = $request->query('type', 'all');
        $filterStatus = $request->query('status', 'all');
        $searchQuery = $request->query('q', '');

        $query = Property::where('owner_id', $userId);

        if ($filterType !== 'all') {
            $query->where('property_type', $filterType);
        }

        if ($filterStatus !== 'all') {
            if ($filterStatus === 'active') {
                $query->where('status', 'available');
            } else {
                $query->where('status', '!=', 'available');
            }
        }

        if (!empty($searchQuery)) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhere('address', 'like', "%{$searchQuery}%");
            });
        }

        $properties = $query->orderBy('created_at', 'DESC')->get();

        // Calculate stats
        $allUserProperties = Property::where('owner_id', $userId)->get();
        $stats = [
            'total' => $allUserProperties->count(),
            'active' => $allUserProperties->where('status', 'available')->count(),
            'inactive' => $allUserProperties->where('status', '!=', 'available')->count(),
            'for_sale' => $allUserProperties->where('listing_type', 'sale')->count(),
            'for_rent' => $allUserProperties->where('listing_type', 'rent')->count(),
        ];

        return view('property_owner.my_properties', compact(
            'properties',
            'filterType',
            'filterStatus',
            'searchQuery',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        return view('property_owner.add_property');
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'property_type' => 'required|in:house,apartment,land,commercial',
            'listing_type' => 'required|in:sale,rent',
            'price' => 'required|numeric|min:1',
            'address' => 'required|string|max:255',
            'price_period' => 'nullable|in:month,year,monthly,yearly',
        ]);

        $userId = Auth::id();

        // Map price period to DB enum: 'once', 'monthly', 'yearly'
        $pricePeriod = 'once';
        if ($request->listing_type === 'rent') {
            $pricePeriod = ($request->price_period === 'year' || $request->price_period === 'yearly') ? 'yearly' : 'monthly';
        }

        $property = Property::create([
            'owner_id' => $userId,
            'property_type' => $request->property_type,
            'listing_type' => $request->listing_type,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'price_period' => $pricePeriod,
            'address' => $request->address,
            'district' => $request->district,
            'sector' => $request->sector,
            'latitude' => $request->latitude ?: null,
            'longitude' => $request->longitude ?: null,
            'status' => 'available',
            'is_verified' => 0,
            'youtube_video_id' => $request->youtube_video_id,
        ]);

        // Insert Features
        if ($request->filled('bedrooms')) {
            PropertyFeature::create([
                'property_id' => $property->id,
                'feature_name' => 'Bedrooms',
                'feature_value' => intval($request->bedrooms),
            ]);
        }
        if ($request->filled('bathrooms')) {
            PropertyFeature::create([
                'property_id' => $property->id,
                'feature_name' => 'Bathrooms',
                'feature_value' => intval($request->bathrooms),
            ]);
        }
        if ($request->filled('size')) {
            PropertyFeature::create([
                'property_id' => $property->id,
                'feature_name' => 'Size (sqm)',
                'feature_value' => intval($request->size),
            ]);
        }

        // Handle Image Uploads
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/properties');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }

            foreach ($request->file('images') as $key => $file) {
                $newFileName = 'prop_' . uniqid() . '_' . $key . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $newFileName);

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_url' => 'public/uploads/properties/' . $newFileName,
                    'sort_order' => $key,
                    'alt_text' => $property->title,
                ]);
            }
        }

        return redirect()->route('property_owner.properties.index')->with('success', 'Property listing created successfully!');
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit($id)
    {
        $userId = Auth::id();
        $property = Property::where('id', $id)->where('owner_id', $userId)->firstOrFail();

        // Features mapped by lowercase name
        $featuresRaw = PropertyFeature::where('property_id', $id)->get();
        $features = [];
        foreach ($featuresRaw as $f) {
            $features[strtolower($f->feature_name)] = $f;
        }

        $images = PropertyImage::where('property_id', $id)->orderBy('sort_order', 'ASC')->get();

        return view('property_owner.edit_property', compact('property', 'features', 'images'));
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $property = Property::where('id', $id)->where('owner_id', $userId)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'property_type' => 'required|in:house,apartment,land,commercial',
            'listing_type' => 'required|in:sale,rent',
            'price' => 'required|numeric|min:1',
            'address' => 'required|string|max:255',
            'status' => 'required|in:available,pending,rented,sold',
            'price_period' => 'nullable|in:month,year,monthly,yearly',
        ]);

        // Map price period to DB enum: 'once', 'monthly', 'yearly'
        $pricePeriod = 'once';
        if ($request->listing_type === 'rent') {
            $pricePeriod = ($request->price_period === 'year' || $request->price_period === 'yearly') ? 'yearly' : 'monthly';
        }

        $property->update([
            'property_type' => $request->property_type,
            'listing_type' => $request->listing_type,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'price_period' => $pricePeriod,
            'address' => $request->address,
            'district' => $request->district,
            'sector' => $request->sector,
            'latitude' => $request->latitude ?: null,
            'longitude' => $request->longitude ?: null,
            'status' => $request->status,
            'youtube_video_id' => $request->youtube_video_id,
        ]);

        // Update features by recreating them
        PropertyFeature::where('property_id', $id)->delete();
        if ($request->filled('bedrooms')) {
            PropertyFeature::create([
                'property_id' => $id,
                'feature_name' => 'Bedrooms',
                'feature_value' => intval($request->bedrooms),
            ]);
        }
        if ($request->filled('bathrooms')) {
            PropertyFeature::create([
                'property_id' => $id,
                'feature_name' => 'Bathrooms',
                'feature_value' => intval($request->bathrooms),
            ]);
        }
        if ($request->filled('size')) {
            PropertyFeature::create([
                'property_id' => $id,
                'feature_name' => 'Size (sqm)',
                'feature_value' => intval($request->size),
            ]);
        }

        // Handle Image Uploads
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/properties');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }

            // Get max sort order
            $maxSort = PropertyImage::where('property_id', $id)->max('sort_order') ?? -1;

            foreach ($request->file('images') as $key => $file) {
                $newFileName = 'prop_' . uniqid() . '_' . $key . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $newFileName);

                PropertyImage::create([
                    'property_id' => $id,
                    'image_url' => 'public/uploads/properties/' . $newFileName,
                    'sort_order' => $maxSort + 1 + $key,
                    'alt_text' => $property->title,
                ]);
            }
        }

        return redirect()->route('property_owner.properties.index')->with('success', 'Property listing updated successfully!');
    }

    /**
     * Delete an image from property.
     */
    public function deleteImage($id, Request $request)
    {
        $userId = Auth::id();
        $property = Property::where('id', $id)->where('owner_id', $userId)->firstOrFail();
        
        $imageId = $request->input('delete_image_id');
        if ($imageId) {
            $image = PropertyImage::where('id', $imageId)->where('property_id', $id)->first();
            if ($image) {
                // Delete physical file
                $filePath = public_path(str_replace('public/', '', $image->image_url));
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $image->delete();
                return redirect()->back()->with('success', 'Image deleted successfully.');
            }
        }
        return redirect()->back()->with('error', 'Image not found or access denied.');
    }

    /**
     * Remove the specified property.
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        $property = Property::where('id', $id)->where('owner_id', $userId)->firstOrFail();

        // Delete physical files
        $images = PropertyImage::where('property_id', $id)->get();
        foreach ($images as $img) {
            $filePath = public_path(str_replace('public/', '', $img->image_url));
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        // Delete relations
        PropertyFeature::where('property_id', $id)->delete();
        PropertyImage::where('property_id', $id)->delete();

        // Delete property
        $property->delete();

        return redirect()->route('property_owner.properties.index')->with('success', 'Listing deleted successfully.');
    }

    /**
     * Bulk remove specified properties.
     */
    public function bulkDestroy(Request $request)
    {
        $userId = Auth::id();
        $propertyIds = $request->input('property_ids', []);

        if (empty($propertyIds)) {
            return redirect()->route('property_owner.properties.index')->with('error', 'No properties selected for deletion.');
        }

        $properties = Property::whereIn('id', $propertyIds)->where('owner_id', $userId)->get();
        $deletedCount = 0;

        foreach ($properties as $property) {
            // Delete physical files
            $images = PropertyImage::where('property_id', $property->id)->get();
            foreach ($images as $img) {
                $filePath = public_path(str_replace('public/', '', $img->image_url));
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            // Delete relations
            PropertyFeature::where('property_id', $property->id)->delete();
            PropertyImage::where('property_id', $property->id)->delete();

            // Delete property
            $property->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            return redirect()->route('property_owner.properties.index')->with('success', "$deletedCount listing(s) deleted successfully.");
        }

        return redirect()->route('property_owner.properties.index')->with('error', 'Could not delete the selected properties.');
    }
}
