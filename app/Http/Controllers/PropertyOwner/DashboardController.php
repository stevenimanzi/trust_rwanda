<?php

namespace App\Http\Controllers\PropertyOwner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display the property owner dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Fetch properties for calculations
        $allProperties = Property::where('owner_id', $userId)->orderBy('created_at', 'DESC')->get();

        $totalValue = $allProperties->sum('price');
        $totalProperties = $allProperties->count();
        $activeListings = $allProperties->where('status', 'available')->count();
        $pendingListings = $allProperties->where('status', 'pending')->count();

        // Ratios
        $activeRatio = $totalProperties > 0 ? round(($activeListings / $totalProperties) * 100) : 0;
        $pendingRatio = $totalProperties > 0 ? round(($pendingListings / $totalProperties) * 100) : 0;
        $verifiedRatio = $user->is_verified ? 100 : 60;

        $saleCount = $allProperties->where('listing_type', 'sale')->count();
        $rentCount = $allProperties->where('listing_type', 'rent')->count();

        $avgPrice = $totalProperties > 0 ? ($totalValue / $totalProperties) : 0;
        $rentRatio = $totalProperties > 0 ? round(($rentCount / $totalProperties) * 100) : 0;
        $saleRatio = $totalProperties > 0 ? round(($saleCount / $totalProperties) * 100) : 0;

        $totalInquiries = Message::where('receiver_id', $userId)->count();
        $inquiriesRead = Message::where('receiver_id', $userId)->where('is_read', 1)->count();
        $responseRatio = $totalInquiries > 0 ? round(($inquiriesRead / $totalInquiries) * 100) : 100;

        $totalViews = 0; // Mock views count as it's not yet tracked for properties
        
        $points = [
            ['x' => 10, 'y' => 200, 'val' => 45],
            ['x' => 20, 'y' => 150, 'val' => 120],
            ['x' => 30, 'y' => 100, 'val' => 300],
            ['x' => 40, 'y' => 120, 'val' => 250],
            ['x' => 50, 'y' => 80, 'val' => 400],
            ['x' => 60, 'y' => 60, 'val' => 500],
            ['x' => 70, 'y' => 40, 'val' => 650]
        ];

        $latestDbProperties = $allProperties->take(4);

        return view('property_owner.dashboard', compact(
            'allProperties',
            'totalValue',
            'totalProperties',
            'activeListings',
            'pendingListings',
            'activeRatio',
            'pendingRatio',
            'verifiedRatio',
            'saleCount',
            'rentCount',
            'avgPrice',
            'rentRatio',
            'saleRatio',
            'responseRatio',
            'totalInquiries',
            'totalViews',
            'points',
            'latestDbProperties'
        ));
    }

    /**
     * Display inquiries feed.
     */
    public function inquiries()
    {
        $userId = Auth::id();
        $inquiries = Message::where('receiver_id', $userId)
            ->with('sender')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('property_owner.inquiries', compact('inquiries'));
    }

    /**
     * Delete an inquiry.
     */
    public function deleteInquiry($id)
    {
        $userId = Auth::id();
        $inquiry = Message::where('id', $id)->where('receiver_id', $userId)->firstOrFail();
        $inquiry->delete();

        return redirect()->route('property_owner.inquiries')->with('success', 'Inquiry deleted successfully.');
    }

    /**
     * Display settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('property_owner.settings', compact('user'));
    }

    /**
     * Update user profile settings.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $fullName = trim($request->first_name . ' ' . $request->last_name);

        $updateData = [
            'full_name' => $fullName,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // Map company_name to shop_name
        if ($request->has('company_name')) {
            $updateData['shop_name'] = $request->company_name;
        }

        // Map bio to shop_description
        if ($request->has('bio')) {
            $updateData['shop_description'] = $request->bio;
        }

        User::where('id', $user->id)->update($updateData);

        return redirect()->route('property_owner.settings')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('property_owner.settings')->with('error', 'Current password is incorrect.');
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('property_owner.settings')->with('success', 'Password updated successfully!');
    }

    /**
     * Update notification preferences.
     */
    public function updatePreferences(Request $request)
    {
        // Safe check to see if database columns exist, else simulate
        $user = Auth::user();
        $updateData = [];

        if (Schema::hasColumn('users', 'notify_inquiries')) {
            $updateData['notify_inquiries'] = $request->has('notify_inquiries') ? 1 : 0;
        }
        if (Schema::hasColumn('users', 'notify_emails')) {
            $updateData['notify_emails'] = $request->has('notify_emails') ? 1 : 0;
        }
        if (Schema::hasColumn('users', 'notify_sms')) {
            $updateData['notify_sms'] = $request->has('notify_sms') ? 1 : 0;
        }

        if (!empty($updateData)) {
            User::where('id', $user->id)->update($updateData);
        }

        return redirect()->route('property_owner.settings')->with('success', 'Preferences updated successfully!');
    }
}
