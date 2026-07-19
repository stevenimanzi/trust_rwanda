<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    // 1. Settings index form
    public function index()
    {
        $vendorId = Auth::id();
        $vendor = User::findOrFail($vendorId);
        
        $displayShopName = $vendor->shop_name ?? $vendor->full_name;
        $initLat = $vendor->latitude ?? -1.9441;
        $initLng = $vendor->longitude ?? 30.0619;

        return view('vendor.settings', compact('vendor', 'displayShopName', 'initLat', 'initLng'));
    }

    // 2. Update Profile & Location details
    public function updateProfile(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:120',
            'full_name' => 'required|string|max:120',
            'phone' => 'required|string|max:20',
            'shop_description' => 'nullable|string|max:2000',
            'address' => 'nullable|string|max:220',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'shop_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072'
        ]);

        $vendorId = Auth::id();
        $vendor = User::findOrFail($vendorId);

        $updateData = [
            'shop_name' => $request->input('shop_name'),
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'shop_description' => $request->input('shop_description'),
            'address' => $request->input('address'),
        ];

        if ($request->filled('latitude')) {
            $updateData['latitude'] = $request->input('latitude');
        }
        if ($request->filled('longitude')) {
            $updateData['longitude'] = $request->input('longitude');
        }

        // Logo upload logic
        if ($request->hasFile('shop_logo')) {
            $file = $request->file('shop_logo');
            $fileExt = $file->getClientOriginalExtension();
            $fileName = "logo_" . $vendorId . "_" . time() . "." . $fileExt;
            
            $targetDir = public_path('assets/uploads/logos');
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $file->move($targetDir, $fileName);
            
            $updateData['shop_logo'] = $fileName;
            $updateData['logo_url'] = asset('assets/uploads/logos/' . $fileName);
        }

        $vendor->update($updateData);

        return redirect()->route('vendor.settings')->with('msg', 'Profile and Location updated successfully.');
    }

    // 3. Update security credentials / Change Password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_pass' => 'required|string',
            'new_pass' => 'required|string|min:8',
            'confirm_pass' => 'required|string|same:new_pass',
        ]);

        $vendorId = Auth::id();
        $vendor = User::findOrFail($vendorId);

        if (!Hash::check($request->input('current_pass'), $vendor->password)) {
            return redirect()->route('vendor.settings')->with('error', 'Incorrect current password.');
        }

        $vendor->update([
            'password' => Hash::make($request->input('new_pass'))
        ]);

        return redirect()->route('vendor.settings')->with('msg', 'Security credentials updated.');
    }
}
