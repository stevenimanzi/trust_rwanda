@php
if (!function_exists('renderPropertyOwnerSidebarNav')) {
    function renderPropertyOwnerSidebarNav() {
        $routes = [
            'dashboard' => route('property_owner.dashboard'),
            'my_properties' => route('property_owner.properties.index'),
            'inquiries' => route('property_owner.inquiries'),
            'settings' => route('property_owner.settings'),
            'profile' => '/profile',
        ];
        
        $current = request()->url();
        $is_active = function($name) use ($routes, $current) {
            if ($name === 'profile') {
                return request()->is('profile*') ? 'active' : '';
            }
            $target = $routes[$name] ?? '';
            return ($current === $target || request()->is(str_replace(url('/'), '', $target) . '*')) ? 'active' : '';
        };
        
        ?>
        <nav class="po-sidebar-nav">
            <div class="mb-3">
                <a href="<?php echo route('property_owner.properties.create'); ?>" class="btn w-100 fw-bold d-flex align-items-center justify-content-center px-3 py-2.5" style="background: var(--primary); color: white; border-radius: 12px; border: none; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                    <span class="d-flex align-items-center gap-2"><i class="bi bi-plus-circle-fill" style="margin:0; color:white;"></i> Add Property</span>
                </a>
            </div>

            <div class="nav-label">Management</div>
            <a href="<?php echo $routes['dashboard']; ?>" class="po-nav-link <?php echo $is_active('dashboard'); ?>"><i class="bi bi-grid-fill"></i>Dashboard</a>
            <a href="<?php echo $routes['my_properties']; ?>" class="po-nav-link <?php echo $is_active('my_properties'); ?>"><i class="bi bi-houses-fill"></i>My Properties</a>
            <a href="<?php echo $routes['inquiries']; ?>" class="po-nav-link <?php echo $is_active('inquiries'); ?>"><i class="bi bi-chat-dots-fill"></i>Inquiries</a>
            
            <div class="nav-label">Account</div>
            <a href="<?php echo $routes['settings']; ?>" class="po-nav-link <?php echo $is_active('settings'); ?>"><i class="bi bi-gear-fill"></i>Settings</a>
            <a href="<?php echo $routes['profile']; ?>" class="po-nav-link <?php echo $is_active('profile'); ?>"><i class="bi bi-person-circle"></i>My Profile</a>

            <!-- Sidebar Recent Leads block matching Constructive UI reference -->
            <div class="sidebar-user-list">
                <div class="nav-label" style="margin-top:0;">Recent Contacts</div>
                
                <div class="po-sidebar-user-item">
                    <img src="https://ui-avatars.com/api/?name=Alesia+K&background=eff6ff&color=4F46E5&bold=true" class="po-sidebar-user-avatar" alt="User">
                    <span>Alesia K.</span>
                    <span class="po-sidebar-user-status"></span>
                </div>
                <div class="po-sidebar-user-item">
                    <img src="https://ui-avatars.com/api/?name=Boss+Akins&background=f0fdf4&color=10b981&bold=true" class="po-sidebar-user-avatar" alt="User">
                    <span>Boss Akins</span>
                    <span class="po-sidebar-user-status" style="background:#f59e0b;"></span>
                </div>
                <div class="po-sidebar-user-item">
                    <img src="https://ui-avatars.com/api/?name=John+Dunbar&background=fff1f2&color=f43f5e&bold=true" class="po-sidebar-user-avatar" alt="User">
                    <span>John Dunbar</span>
                    <span class="po-sidebar-user-status" style="background:#94a3b8;"></span>
                </div>
            </div>
        </nav>
        <?php
    }
}
@endphp

<div class="po-sidebar" id="propertyOwnerSidebar">
    <div class="po-sidebar-header">
        <a href="{{ route('property_owner.dashboard') }}" class="text-dark text-decoration-none d-flex align-items-center gap-2">
            <div class="logo-img-wrapper">
                <img src="{{ asset('assets/uploads/logos/TrustRwanda-Logo.png') }}" class="img-fluid rounded-2" alt="Logo" style="height: 24px;">
            </div>
            <div>
                <div class="fw-bold lh-1 text-dark" style="font-size: 0.95rem; letter-spacing: -0.5px;">Trust Rwan<span class="text-primary" style="color:var(--primary) !important;">PRO</span></div>
                <div style="font-size: 0.58rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-top: 1px;">OWNER DASH</div>
            </div>
        </a>
    </div>

    @php renderPropertyOwnerSidebarNav(); @endphp

    <div class="footer-identity">
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit"><i class="bi bi-power"></i> LOGOUT</button>
        </form>
    </div>
</div>

<div class="mobile-bottom-nav">
    <a href="{{ route('property_owner.dashboard') }}" class="m-nav-item {{ request()->routeIs('property_owner.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-fill"></i><span>Home</span>
    </a>
    <a href="{{ route('property_owner.properties.index') }}" class="m-nav-item {{ request()->routeIs('property_owner.properties.index') ? 'active' : '' }}">
        <i class="bi bi-houses-fill"></i><span>Properties</span>
    </a>
    <a href="{{ route('property_owner.properties.create') }}" class="m-nav-item {{ request()->routeIs('property_owner.properties.create') ? 'active' : '' }}">
        <i class="bi bi-plus-circle-fill" style="color: var(--primary); font-size: 1.5rem; margin-top: -3px;"></i>
        <span style="color: var(--primary);">Add</span>
    </a>
    <a href="{{ route('property_owner.inquiries') }}" class="m-nav-item {{ request()->routeIs('property_owner.inquiries') ? 'active' : '' }}">
        <i class="bi bi-chat-dots-fill"></i><span>Inquiries</span>
    </a>
    <a href="{{ route('property_owner.settings') }}" class="m-nav-item {{ request()->routeIs('property_owner.settings') ? 'active' : '' }}">
        <i class="bi bi-gear-fill"></i><span>Settings</span>
    </a>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" style="width: 260px; background-color: var(--sidebar-bg);">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold text-dark"><i class="bi bi-building me-2 text-primary"></i>Property Owner</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        @php renderPropertyOwnerSidebarNav(); @endphp
    </div>
</div>
