@php
$currentUser = auth()->user();
@endphp
<header class="po-topbar">
    <div class="po-topbar-left">
        <!-- Mobile Menu Toggle -->
        <button class="btn border-0 d-lg-none me-2 p-1 po-mobile-toggle" id="sidebarToggle" aria-label="Toggle sidebar menu" style="font-size: 1.5rem; color: var(--text-dark);">
            <i class="bi bi-list"></i>
        </button>

        <!-- Search Bar -->
        <form class="po-search-bar-wrapper d-none d-md-block" method="GET" action="{{ route('property_owner.properties.index') }}">
            <i class="bi bi-search"></i>
            <input type="text" name="q" class="po-search-bar" placeholder="Search properties..." value="{{ request('q') }}">
        </form>
    </div>

    <!-- Actions: Notifications & User Profile -->
    <div class="d-flex align-items-center gap-3">
        <!-- Notifications button -->
        <div class="top-icon-btn position-relative">
            <i class="bi bi-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-danger border border-light rounded-circle" style="width: 8px; height: 8px; background: #fa5252 !important; margin-top: 4px; margin-left: -4px;">
                <span class="visually-hidden">New notifications</span>
            </span>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <div class="po-user-avatar-wrapper" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($currentUser->full_name ?? 'Owner') }}&background=4F46E5&color=fff&rounded=true&bold=true" 
                     alt="User Avatar" 
                     class="po-user-avatar">
                <div class="text-start d-none d-sm-block" style="line-height: 1.2;">
                    <div class="fw-bold text-dark" style="font-size: 0.82rem;">{{ $currentUser->full_name ?? 'Owner' }}</div>
                    <div class="text-muted fw-semibold" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.3px;">Property Owner</div>
                </div>
            </div>
            
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 rounded-3 p-2" style="min-width: 200px; border: 1px solid var(--border-color) !important;">
                <li class="px-3 py-2">
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $currentUser->full_name ?? 'Owner' }}</div>
                    <div class="small text-muted" style="font-size: 0.72rem;">{{ $currentUser->email }}</div>
                </li>
                <li><hr class="dropdown-divider" style="margin: 6px 0; border-color: var(--border-color);"></li>
                <li><a class="dropdown-item rounded-2 py-2 fw-semibold" href="/profile" style="font-size: 0.8rem; color: var(--text-dark);"><i class="bi bi-person me-2"></i> My Profile</a></li>
                <li><a class="dropdown-item rounded-2 py-2 fw-semibold" href="{{ route('property_owner.settings') }}" style="font-size: 0.8rem; color: var(--text-dark);"><i class="bi bi-gear me-2"></i> Settings</a></li>
                <li><hr class="dropdown-divider" style="margin: 6px 0; border-color: var(--border-color);"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item rounded-2 text-danger fw-bold py-2" style="font-size: 0.8rem; border: none; background: none; width: 100%; text-align: left;"><i class="bi bi-box-arrow-left me-2"></i> Sign Out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
