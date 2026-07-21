<?php

use Illuminate\Support\Facades\Route;

// Property Owner Controllers
use App\Http\Controllers\PropertyOwner\DashboardController as PODashboardController;
use App\Http\Controllers\PropertyOwner\PropertyController as POPropertyController;

// Vendor Controllers
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Vendor\AnalyticsController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\PromotionController;
use App\Http\Controllers\Vendor\EarningsController;
use App\Http\Controllers\Vendor\SettingsController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserSettingsController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\PromoRequestController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\NewsletterController;

// Public Storefront Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC STOREFRONT ROUTES
// ==========================================
// Static Pages
Route::get('/help-support', [PageController::class, 'helpSupport'])->name('pages.help');
Route::get('/staff', [PageController::class, 'staff'])->name('pages.staff');
Route::get('/team', [PageController::class, 'team'])->name('pages.team');
Route::get('/careers', [PageController::class, 'careers'])->name('pages.careers');
Route::get('/blog', [PageController::class, 'blog'])->name('pages.blog');
Route::get('/security', [PageController::class, 'security'])->name('pages.security');
Route::get('/global', [PageController::class, 'global'])->name('pages.global');
Route::get('/charts', [PageController::class, 'charts'])->name('pages.charts');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/compliances', [PageController::class, 'compliances'])->name('pages.compliances');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/properties', [App\Http\Controllers\FrontendPropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{id}', [App\Http\Controllers\FrontendPropertyController::class, 'show'])->name('properties.show');
Route::post('/products/{id}/review', [ProductController::class, 'storeReview'])->name('products.review');
Route::get('/farmers-market', [ProductController::class, 'farmersMarket'])->name('farmers.market');
Route::get('/nearby-shops', [ProductController::class, 'nearbyShops'])->name('nearby.shops');

// Cart & Checkout Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place');
Route::get('/order-success', [CartController::class, 'success'])->name('order.success');

// Payment Routes
Route::get('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
Route::match(['get', 'post'], '/api/pesapal/ipn', [\App\Http\Controllers\PaymentController::class, 'ipn'])->name('api.pesapal.ipn');

// Affiliate Routes
Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');

// Global Ajax Actions
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/wishlist/toggle', function() {
    return response()->json(['status' => 'success', 'action' => 'added']);
})->name('wishlist.toggle');

// Fallback stubs for legacy links referenced in layout
Route::any('/real_estate', function() {
    return redirect()->route('properties.index', ['category' => 'all']);
})->name('real_estate');

// ==========================================
// 2. GUEST AUTH ROUTES
// ==========================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Buyer Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Vendor Registration
    Route::get('/vendor-register', [AuthController::class, 'showVendorRegister'])->name('vendor.register');
    Route::post('/vendor-register', [AuthController::class, 'vendorRegister']);

    // Property Owner Registration
    Route::get('/property-owner-register', [AuthController::class, 'showPropertyOwnerRegister'])->name('property_owner.register');
    Route::post('/property-owner-register', [AuthController::class, 'propertyOwnerRegister']);

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

    // Reset Password
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==========================================
// 3. AUTHENTICATED PROFILE & LOGOUT ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateInfo'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout']);
});

// ==========================================
// 4. PROPERTY OWNER ROUTE GROUP
// ==========================================
Route::middleware(['auth', 'role:real_estate_owner'])->prefix('property_owner')->name('property_owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PODashboardController::class, 'index'])->name('dashboard');
    Route::get('/inquiries', [PODashboardController::class, 'inquiries'])->name('inquiries');
    Route::delete('/inquiries/{inquiry}', [PODashboardController::class, 'deleteInquiry'])->name('inquiries.destroy');

    // Properties Management CRUD
    Route::post('/properties/bulk-delete', [POPropertyController::class, 'bulkDestroy'])->name('properties.bulk_destroy');
    Route::post('/properties/{property}/delete-image', [POPropertyController::class, 'deleteImage'])->name('properties.delete_image');
    Route::resource('/properties', POPropertyController::class);

    // Settings Profile & Password Management
    Route::get('/settings', [PODashboardController::class, 'settings'])->name('settings');
    Route::put('/settings/profile', [PODashboardController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [PODashboardController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/preferences', [PODashboardController::class, 'updatePreferences'])->name('settings.preferences');
});

// ==========================================
// 5. VENDOR PORTAL ROUTE GROUP
// ==========================================
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/customers', [VendorController::class, 'customers'])->name('customers');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    
    // Products Catalog
    Route::get('/products', [VendorProductController::class, 'index'])->name('products');
    Route::get('/products/create', [VendorProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [VendorProductController::class, 'store'])->name('products.store');
    Route::post('/products/delete', [VendorProductController::class, 'destroy'])->name('products.delete');
    Route::post('/products/bulk-delete', [VendorProductController::class, 'bulkDestroy'])->name('products.bulk_delete');
    Route::post('/products/quick-update', [VendorProductController::class, 'quickUpdate'])->name('products.quick_update');
    Route::get('/products/{id}/edit', [VendorProductController::class, 'edit'])->name('products.edit');
    Route::post('/products/{id}', [VendorProductController::class, 'update'])->name('products.update');
    
    // Stock Alerts / Inventory Management
    Route::match(['get', 'post'], '/inventory-mgmt', [VendorProductController::class, 'inventoryMgmt'])->name('inventory');
    
    // Shop Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'details'])->name('orders.details');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update_status');
    
    // Growth / Promotions
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions');
    Route::post('/promotions', [PromotionController::class, 'requestPromo'])->name('promotions.submit');
    
    // Account / Earnings
    Route::get('/earnings', [EarningsController::class, 'index'])->name('earnings');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/security', [SettingsController::class, 'changePassword'])->name('settings.security');
});

// ==========================================
// 6. ADMIN PORTAL ROUTE GROUP
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/global-search', [AdminController::class, 'globalSearch'])->name('global_search');
    Route::post('/notifications/mark-read', [AdminController::class, 'markNotificationsRead'])->name('notifications.mark_read');

    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/sms-logs', [AdminController::class, 'smsLogs'])->name('sms_logs');
    Route::post('/sms-logs/broadcast', [AdminController::class, 'broadcastSms'])->name('sms_logs.broadcast');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::post('/users/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::get('/users/add', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/add', [UserController::class, 'store'])->name('users.store');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products/toggle', [AdminProductController::class, 'toggleStatus'])->name('products.toggle');
    Route::post('/products/delete', [AdminProductController::class, 'delete'])->name('products.delete');

    Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [UserSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [UserSettingsController::class, 'changePassword'])->name('settings.password');
    Route::post('/settings/branding', [UserSettingsController::class, 'saveBranding'])->name('settings.branding');
    Route::post('/settings/business', [UserSettingsController::class, 'saveBusiness'])->name('settings.business');
    Route::post('/settings/maintenance', [UserSettingsController::class, 'toggleMaintenance'])->name('settings.maintenance');
    Route::post('/settings/backup', [UserSettingsController::class, 'backupDatabase'])->name('settings.backup');
    Route::post('/settings/ops/optimize', [UserSettingsController::class, 'optimizeSystem'])->name('settings.ops.optimize');
    Route::post('/settings/ops/storage-link', [UserSettingsController::class, 'storageLink'])->name('settings.ops.storage_link');
    Route::get('/settings/ops/logs', [UserSettingsController::class, 'downloadLogs'])->name('settings.ops.logs.download');
    Route::post('/settings/ops/logs/clear', [UserSettingsController::class, 'clearLogs'])->name('settings.ops.logs.clear');

    Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
    Route::post('/ads/deploy', [AdController::class, 'deployCampaign'])->name('ads.deploy');
    Route::post('/ads/inquiry-status', [AdController::class, 'updateInquiryStatus'])->name('ads.inquiry_status');
    Route::get('/ads/manage', [AdController::class, 'manage'])->name('ads.manage');
    Route::post('/ads/manage/create', [AdController::class, 'createAd'])->name('ads.create');
    Route::post('/ads/manage/update-status', [AdController::class, 'updateAdStatus'])->name('ads.update_status');
    Route::post('/ads/manage/delete', [AdController::class, 'deleteAd'])->name('ads.delete');
    Route::post('/ads/manage/notify', [AdController::class, 'pushNotification'])->name('ads.notify');

    Route::get('/promo-requests', [PromoRequestController::class, 'index'])->name('promo_requests.index');
    Route::post('/promo-requests/approve', [PromoRequestController::class, 'approve'])->name('promo_requests.approve');
    Route::post('/promo-requests/reject', [PromoRequestController::class, 'reject'])->name('promo_requests.reject');

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/add', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('/subscriptions/revoke', [SubscriptionController::class, 'revoke'])->name('subscriptions.revoke');

    Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');

    Route::get('/email-blast', [NewsletterController::class, 'index'])->name('email_blast.index');
    Route::post('/email-blast/send', [NewsletterController::class, 'sendBlast'])->name('email_blast.send');
});
