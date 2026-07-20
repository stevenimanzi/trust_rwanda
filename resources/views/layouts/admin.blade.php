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
    $dbNotifications = \App\Models\Notification::where('user_id', $_adminUser->id)->where('is_read', 0)->orderBy('created_at', 'desc')->get();
    $totalNotifications += $dbNotifications->count();
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
        <a href="{{ route('admin.users.index', ['role' => 'vendor']) }}" class="hz-nav-link {{ request('role') == 'vendor' ? 'active' : '' }}">
            <i class="bi bi-shop"></i> Vendors 
            @if($pendingVendorsCount > 0)
                <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingVendorsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.users.index') }}" class="hz-nav-link {{ request()->routeIs('admin.users.*') && !request('role') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Users
        </a>
    </div>

    <!-- Operations -->
    <div class="hz-nav-group">
        <div class="hz-nav-label">Operations</div>
        <a href="{{ route('admin.products.index') }}" class="hz-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> Products
        </a>
        <a href="#" class="hz-nav-link">
            <i class="bi bi-cart-check"></i> Orders <span class="badge bg-secondary ms-2" style="font-size:0.6rem">Soon</span>
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
    
    <!-- Global Search -->
    <div class="hz-global-search position-relative d-none d-md-block" style="flex:1; max-width: 400px; margin: 0 30px;">
        <div class="position-relative">
            <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted" style="left: 14px;"></i>
            <input type="text" id="globalSearchInput" class="hz-form-control rounded-pill border-0 w-100" placeholder="Search users, orders, products (Press '/')" style="padding-left: 40px; box-shadow: none;">
            <div id="globalSearchSpinner" class="spinner-border spinner-border-sm text-primary position-absolute top-50 translate-middle-y d-none" style="right: 14px;"></div>
        </div>
        <!-- Search Results Dropdown -->
        <div id="globalSearchResults" class="position-absolute w-100 hz-search-dropdown mt-2 d-none" style="z-index: 1050; max-height: 400px; overflow-y: auto;">
            <!-- Content injected via JS -->
        </div>
    </div>

    <div class="hz-top-actions">
        <div class="hz-timer" id="liveClock">
            {{ date('H:i:s') }} 
            <button class="hz-btn-play"><i class="bi bi-play-fill"></i></button>
        </div>
        <div class="d-flex align-items-center gap-2 border-start ps-3 ms-2">
            <button class="hz-icon-btn" onclick="openHelpModal()"><i class="bi bi-question-circle"></i></button>
            
            <!-- Notifications Dropdown -->
            <div class="dropdown">
                <button class="hz-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" onclick="markNotificationsRead()">
                    <i class="bi bi-bell"></i>
                    @if($totalNotifications > 0)
                        <span class="hz-dot" id="notifBadge"></span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-0 mt-2" style="width: 320px;">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top-4">
                        <h6 class="m-0 fw-bold">Notifications</h6>
                        <span class="badge bg-primary rounded-pill">{{ $dbNotifications->count() }} new</span>
                    </div>
                    <div class="notif-list" style="max-height: 300px; overflow-y: auto;">
                        @if($dbNotifications->isEmpty())
                            <div class="p-4 text-center text-muted small">No new notifications.</div>
                        @else
                            @foreach($dbNotifications as $notif)
                                <a href="#" class="dropdown-item py-3 border-bottom text-wrap">
                                    <div class="d-flex gap-3">
                                        <div class="notif-icon text-{{ $notif->type == 'success' ? 'success' : ($notif->type == 'alert' ? 'warning' : 'primary') }}">
                                            <i class="bi bi-info-circle-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">{{ $notif->title }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $notif->message }}</div>
                                            <div class="text-muted mt-1" style="font-size: 0.65rem;">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <div class="p-2 text-center bg-light rounded-bottom-4">
                        <a href="#" class="text-decoration-none small fw-bold text-primary">View all</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown ms-2">
                <img src="https://ui-avatars.com/api/?name=Admin&background=eff6ff&color=4F46E5&bold=true" class="hz-avatar" alt="User Avatar" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2 py-2">
                    <li>
                        <a class="dropdown-item py-2 fw-bold" href="{{ route('admin.settings.index') }}#section-profile">
                            <i class="bi bi-person-circle me-2 text-primary"></i> My Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider opacity-10"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 fw-bold text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
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
<script>
    // 1. Live Clock Logic
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour12: false });
        const timerContainer = document.getElementById('liveClock');
        if (timerContainer) {
            timerContainer.innerHTML = timeString + ' <button class="hz-btn-play text-success"><i class="bi bi-activity"></i></button>';
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. Help Modal (Pro Feature)
    window.openHelpModal = function() {
        Swal.fire({
            title: 'Admin Intelligence Center',
            html: '<p>Welcome to your Pro Dashboard.</p><p class="small text-muted">Need assistance? Reach out to <b>support@trustrwanda.com</b> or use the Global Search (Press "/") to quickly navigate.</p>',
            icon: 'info',
            confirmButtonText: 'Got it!'
        });
    };

    // 3. Mark Notifications Read
    window.markNotificationsRead = function() {
        const badge = document.getElementById('notifBadge');
        if (badge) {
            fetch("{{ route('admin.notifications.mark_read') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(() => badge.style.display = 'none');
        }
    };

    // 4. Global Search Logic
    const searchInput = document.getElementById('globalSearchInput');
    const searchResults = document.getElementById('globalSearchResults');
    const searchSpinner = document.getElementById('globalSearchSpinner');
    let searchDebounce;

    // Keyboard shortcut for search
    document.addEventListener('keydown', e => {
        if (e.key === '/' && document.activeElement !== searchInput) {
            e.preventDefault();
            searchInput.focus();
        }
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchDebounce);
            const q = this.value.trim();
            
            if (q.length < 2) {
                searchResults.classList.add('d-none');
                return;
            }
            
            searchSpinner.classList.remove('d-none');
            
            searchDebounce = setTimeout(() => {
                fetch(`{{ route('admin.global_search') }}?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(data => {
                        let html = '';
                        
                        // Users
                        if (data.users.length) {
                            html += '<div class="hz-search-category">Users</div>';
                            data.users.forEach(u => {
                                const url = `{{ route('admin.users.index') }}?search=${encodeURIComponent(u.full_name)}`;
                                html += `<a href="${url}" class="dropdown-item hz-search-item d-flex align-items-center gap-2"><i class="bi bi-person text-primary"></i> ${u.full_name} <span class="text-muted small">(${u.role})</span></a>`;
                            });
                        }
                        // Products
                        if (data.products.length) {
                            html += '<div class="hz-search-category">Products</div>';
                            data.products.forEach(p => {
                                const url = `{{ route('admin.products.index') }}?search=${encodeURIComponent(p.title)}`;
                                html += `<a href="${url}" class="dropdown-item hz-search-item d-flex align-items-center gap-2"><i class="bi bi-box-seam text-success"></i> ${p.title}</a>`;
                            });
                        }
                        // Orders
                        if (data.orders.length) {
                            html += '<div class="hz-search-category">Orders (Coming Soon)</div>';
                            data.orders.forEach(o => {
                                html += `<a href="#" class="dropdown-item hz-search-item d-flex align-items-center gap-2 text-muted"><i class="bi bi-receipt text-warning"></i> Order #${o.id} - ${o.customer_name}</a>`;
                            });
                        }
                        
                        if (!html) html = '<div class="p-3 text-center text-muted small">No results found.</div>';
                        
                        searchResults.innerHTML = html;
                        searchResults.classList.remove('d-none');
                    })
                    .finally(() => searchSpinner.classList.add('d-none'));
            }, 300);
        });

        // Hide search on outside click
        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('d-none');
            }
        });
    }

    // System Toast Notifications
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    @endif
</script>
</body>
</html>
