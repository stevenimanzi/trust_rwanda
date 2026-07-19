@php
    $_adminUser = auth()->user();
    $_sysLogo = \App\Models\SystemSetting::where('setting_key', 'site_logo')->value('setting_value');
    $_sysName = \App\Models\SystemSetting::where('setting_key', 'site_name')->value('setting_value');
    $_adminSiteName = $_sysName ?: 'Trust Rwanda';
    $_adminSiteLogo = $_sysLogo ? kura_logo_image_url($_sysLogo) : kura_default_logo_image_url();

    // Counts for notifications badge
    $pendingOrdersCount = \App\Models\Order::where('delivery_status', 'pending')->count();
    $pendingVendorsCount = \App\Models\User::where('role', 'vendor')->where('is_verified', 0)->count();
    $totalNotifications = $pendingOrdersCount + $pendingVendorsCount;
    $unreadMessages = $_adminUser ? \App\Models\Message::where('receiver_id', $_adminUser->id)->where('is_read', 0)->count() : 0;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin Pro') | {{ $_adminSiteName }}</title>
    
    @if($_sysLogo)
    <link rel="icon" href="{{ kura_logo_image_url($_sysLogo) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Trust Rwanda Core Styles -->
    <link href="{{ asset('assets/css/horizon.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body class="hz-dashboard">

<!-- HORIZON SIDEBAR -->
<nav class="hz-sidebar">
    <div class="hz-logo-area">
        <div class="hz-logo-icon">
            <img src="{{ $_adminSiteLogo }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 10px;">
        </div>
        <div class="hz-logo-text">{{ $_adminSiteName }}</div>
    </div>

    <!-- Management -->
    <div class="hz-nav-group">
        <div class="hz-nav-label">Management</div>
        <a href="{{ route('admin.dashboard') }}" class="hz-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.vendors.index') }}" class="hz-nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
            <i class="bi bi-shop-window"></i> Vendors 
            @if($pendingVendorsCount > 0)
                <span class="hz-nav-badge" style="background: var(--hz-primary);">{{ $pendingVendorsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.users.index') }}" class="hz-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Users
        </a>
    </div>

    <!-- Operations -->
    <div class="hz-nav-group">
        <div class="hz-nav-label">Operations</div>
        <a href="{{ route('admin.products.index') }}" class="hz-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> Products
        </a>
        <a href="{{ route('admin.orders.index') }}" class="hz-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i> Orders
            @if($pendingOrdersCount > 0)
                <span class="hz-nav-badge" style="background: var(--hz-secondary);">{{ $pendingOrdersCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.promo_requests.index') }}" class="hz-nav-link {{ request()->routeIs('admin.promo_requests.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill"></i> Promo Requests
        </a>
    </div>

    <!-- System -->
    <div class="hz-nav-group">
        <div class="hz-nav-label">System</div>
        <a href="{{ route('admin.settings.index') }}" class="hz-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i> Settings
        </a>
    </div>

    <!-- Workspace -->
    <div class="hz-workspace" onclick="document.getElementById('logout-form').submit();">
        <div class="hz-ws-icon">
            <i class="bi bi-power"></i>
        </div>
        <div class="hz-ws-info">
            <div class="hz-ws-label">Admin Portal</div>
            <div class="hz-ws-name">{{ $_adminUser->name ?? 'Administrator' }}</div>
        </div>
        <i class="bi bi-chevron-expand text-muted"></i>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</nav>

<!-- HORIZON TOPNAV -->
<header class="hz-topnav">
    <div class="hz-page-title">
        <i class="bi bi-grid"></i> Dashboard <i class="bi bi-chevron-down ms-2 text-muted" style="font-size:0.7rem;"></i>
    </div>
    
    <div class="hz-top-actions">
        <div class="hz-timer">
            {{ date('H:i:s') }} 
            <button class="hz-btn-play"><i class="bi bi-play-fill"></i></button>
        </div>
        <div class="d-flex align-items-center gap-2 border-start ps-3 ms-2">
            <button class="hz-icon-btn"><i class="bi bi-question-circle"></i></button>
            <button class="hz-icon-btn">
                <i class="bi bi-bell"></i>
                @if($totalNotifications > 0)
                    <span class="hz-dot"></span>
                @endif
            </button>
            <img src="https://ui-avatars.com/api/?name=Admin&background=eff6ff&color=4F46E5&bold=true" class="hz-avatar ms-2" alt="User Avatar">
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<div class="container-fluid" style="padding: 32px;">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Replace native confirm() with beautiful internal SweetAlert2 popups
    document.addEventListener('DOMContentLoaded', function() {
        // Handle forms with onsubmit containing 'confirm'
        document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
            const onsubmitStr = form.getAttribute('onsubmit');
            const match = onsubmitStr.match(/confirm\(['"](.+?)['"]\)/);
            if (match) {
                const message = match[1];
                form.removeAttribute('onsubmit');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#f43f5e',
                        confirmButtonText: 'Yes, proceed',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });

        // Handle buttons/links with onclick containing 'confirm'
        document.querySelectorAll('[onclick*="confirm"]').forEach(el => {
            const onclickStr = el.getAttribute('onclick');
            const match = onclickStr.match(/confirm\(['"](.+?)['"]\)/);
            if (match) {
                const message = match[1];
                el.removeAttribute('onclick');
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#f43f5e',
                        confirmButtonText: 'Yes, proceed',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (el.tagName.toLowerCase() === 'a' && el.href) {
                                window.location.href = el.href;
                            } else if (el.tagName.toLowerCase() === 'button' && el.type === 'submit') {
                                const form = el.closest('form');
                                if (form) form.submit();
                            }
                        }
                    });
                });
            }
        });
    });
</script>
<!-- SweetAlert2 for Custom Popups -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@yield('scripts')
</body>
</html>
