<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display users list
     */
    public function index(Request $request)
    {
        $filterRole = $request->query('role', 'all');
        $search = $request->query('q', '');

        $query = User::query();

        // Join Subscriptions to get subscription end date compatibility
        $query->select('users.*')
            ->selectSub(function ($q) {
                $q->select('end_date')
                    ->from('subscriptions')
                    ->whereColumn('subscriptions.user_id', 'users.id')
                    ->orderBy('end_date', 'desc')
                    ->limit(1);
            }, 'sub_end_date')
            ->selectSub(function ($q) {
                $q->selectRaw('SUM(quantity * price_at_purchase)')
                    ->from('order_items')
                    ->whereColumn('order_items.vendor_id', 'users.id');
            }, 'vendor_revenue')
            ->selectSub(function ($q) {
                $q->selectRaw('COUNT(DISTINCT order_id)')
                    ->from('order_items')
                    ->whereColumn('order_items.vendor_id', 'users.id');
            }, 'vendor_fulfillments')
            ->withCount('products');

        if ($filterRole !== 'all') {
            $query->where('role', $filterRole);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('shop_name', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate Stats
        $totalUsers = User::count();
        $totalVendors = User::where('role', 'vendor')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $verifiedCount = User::where('is_verified', 1)->count();

        return view('admin.users', compact(
            'users', 'filterRole', 'search', 'totalUsers', 
            'totalVendors', 'totalCustomers', 'verifiedCount'
        ));
    }

    /**
     * Edit user node details
     */
    public function edit(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'role' => 'required|in:customer,vendor,admin',
            'shop_name' => 'nullable|string|max:255',
            'new_password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'shop_description' => 'nullable|string',
        ]);

        $updateData = [
            'full_name' => trim($request->input('full_name')),
            'email' => trim($request->input('email')),
            'role' => $request->input('role'),
            'phone' => trim($request->input('phone')),
            'address' => trim($request->input('address')),
        ];

        if ($request->input('role') === 'vendor') {
            $updateData['shop_name'] = trim($request->input('shop_name'));
            $updateData['shop_description'] = trim($request->input('shop_description'));
        } else {
            $updateData['shop_name'] = null;
            $updateData['shop_description'] = null;
        }

        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->input('new_password'));
        }

        $user->update($updateData);

        return redirect()->back()->with('success', "Account Protocol Updated: " . htmlspecialchars($user->full_name));
    }

    /**
     * Toggle status / verify node
     */
    public function toggleStatus(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));
        $newStatus = (int)$request->input('new_status');
        
        $user->update(['is_verified' => $newStatus]);

        $msg = $newStatus ? "User node authorized." : "User node restricted.";
        return redirect()->back()->with('success', $msg);
    }

    /**
     * Delete user
     */
    public function delete(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        try {
            $user->delete();
            return redirect()->back()->with('success', "User permanently purged from core.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Integrity Constraint Error: cannot delete user with links.");
        }
    }

    /**
     * Show provision user view
     */
    public function create()
    {
        return view('admin.user_add');
    }

    /**
     * Store provisioned user
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'role' => 'required|in:customer,vendor,admin',
            'shop_name' => 'nullable|required_if:role,vendor|string|max:255',
        ]);

        User::create([
            'full_name' => trim($request->input('full_name')),
            'email' => trim($request->input('email')),
            'phone' => trim($request->input('phone')),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'shop_name' => ($request->input('role') === 'vendor') ? trim($request->input('shop_name')) : null,
            'is_verified' => 1,
        ]);

        return redirect()->route('admin.users.index')->with('success', "Account Protocol Deployed Successfully.");
    }
}
