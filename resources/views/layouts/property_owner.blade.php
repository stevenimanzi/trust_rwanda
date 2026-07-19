@php
    $poUser = auth()->user();
    $sysSetting = \App\Models\SystemSetting::where('setting_key', 'site_logo')->first();
    $logoUrl = $sysSetting && $sysSetting->setting_value 
        ? asset('assets/uploads/logos/' . $sysSetting->setting_value) 
        : asset('assets/uploads/logos/TrustRwanda-Logo.jpg');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Trust Rwanda Real Estate</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- E-Commerce Core Styles -->
    <link href="{{ asset('assets/css/horizon.css') }}" rel="stylesheet">
    
    <style>
        .hz-dashboard-search {
            background: var(--hz-bg);
            border-radius: 20px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 300px;
            border: 1px solid var(--hz-border);
        }
        .hz-dashboard-search input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.9rem;
            color: var(--hz-text-main);
        }
        .hz-dashboard-search input::placeholder {
            color: var(--hz-text-light);
        }
        .hz-icon-btn-light {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--hz-primary-light);
            color: var(--hz-primary);
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
    </style>
    @yield('styles')
</head>
<body class="hz-dashboard" style="background: #eef2ff !important;">

<!-- E-COMMERCE SIDEBAR -->
<nav class="hz-sidebar">
    <div class="hz-logo-area">
        <div class="hz-logo-icon" style="background: transparent; color: var(--hz-primary); font-size: 24px;">
            <i class="bi bi-buildings-fill"></i>
        </div>
        <div class="hz-logo-text">Trust Rwanda</div>
    </div>

    <div class="d-flex flex-column gap-1 w-100">
        <a href="{{ route('property_owner.dashboard') }}" class="hz-nav-link {{ request()->routeIs('property_owner.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a href="{{ route('property_owner.properties.index') }}" class="hz-nav-link {{ request()->routeIs('property_owner.properties.index') ? 'active' : '' }}">
            <i class="bi bi-houses"></i> My Properties
        </a>
        <a href="{{ route('property_owner.properties.create') }}" class="hz-nav-link {{ request()->routeIs('property_owner.properties.create') ? 'active' : '' }}">
            <i class="bi bi-plus-lg"></i> List New Property
        </a>
        
        <div style="flex-grow: 1;"></div>
        
        <hr>
        
        <a href="{{ route('property_owner.settings') }}" class="hz-nav-link {{ request()->routeIs('property_owner.settings') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        <a href="#" class="hz-nav-link" onclick="document.getElementById('logout-form').submit(); return false;">
            <i class="bi bi-box-arrow-right"></i> Log Out
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</nav>

<!-- TOPNAV -->
<header class="hz-topnav bg-white shadow-sm" style="height: 80px; padding: 0 32px; display:flex; align-items:center; justify-content:space-between;">
    <div class="d-flex align-items-center gap-3">
        <button class="hz-mobile-toggle" onclick="document.querySelector('.hz-sidebar').classList.toggle('show')">
            <i class="bi bi-list"></i>
        </button>
        <div class="hz-page-title fs-5 fw-bold text-dark mb-0">
            Hello, {{ explode(' ', $poUser->name ?? 'Owner')[0] }} 👋
        </div>
    </div>
    
    <div class="hz-dashboard-search position-relative">
        <i class="bi bi-search text-muted"></i>
        <input type="text" id="quickSearchInput" placeholder="Search features, pages, tools..." autocomplete="off">
        
        <!-- Search Dropdown -->
        <div id="quickSearchDropdown" class="position-absolute bg-white shadow-lg rounded-3 w-100 mt-2 d-none" style="top: 100%; left: 0; z-index: 1000; border: 1px solid #e2e8f0; max-height: 300px; overflow-y: auto;">
            <div class="px-3 py-2 text-muted small fw-bold text-uppercase border-bottom bg-light">Quick Links</div>
            <div id="quickSearchResults">
                <!-- Results injected here -->
            </div>
        </div>
    </div>
    
    <div class="hz-top-actions d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="hz-icon-btn-light">
                <i class="bi bi-bell"></i>
                <span class="hz-dot" style="background:#ef4444; width:6px; height:6px; top:12px; right:12px;"></span>
            </div>
            <div class="hz-icon-btn-light" style="background:#f3f4f6; color:#6b7280;">
                <i class="bi bi-envelope"></i>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 ms-2 cursor-pointer">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($poUser->name ?? 'Owner') }}&background=1e293b&color=ffffff&bold=true" class="hz-avatar rounded-circle" style="width: 36px; height:36px;" alt="User Avatar">
            <div class="fw-bold" style="font-size:0.9rem;">{{ $poUser->name ?? 'Owner' }}</div>
            <i class="bi bi-chevron-down text-muted" style="font-size:0.7rem;"></i>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<div class="container-fluid" style="padding: 32px;">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Quick Search Logic -->
<script>
    const qsInput = document.getElementById('quickSearchInput');
    const qsDropdown = document.getElementById('quickSearchDropdown');
    const qsResults = document.getElementById('quickSearchResults');

    const searchDictionary = [
        { title: 'Dashboard', keywords: 'home overview dashboard analytics', url: '{{ route("property_owner.dashboard") }}', icon: 'bi-grid' },
        { title: 'My Properties', keywords: 'properties listings manage real estate', url: '{{ route("property_owner.properties.index") }}', icon: 'bi-houses' },
        { title: 'List New Property', keywords: 'create new add property listing upload', url: '{{ route("property_owner.properties.create") }}', icon: 'bi-plus-lg' },
        { title: 'Inquiries', keywords: 'messages contact leads inquiries', url: '{{ route("property_owner.inquiries") }}', icon: 'bi-chat-dots' },
        { title: 'Account Settings', keywords: 'settings profile password', url: '{{ route("property_owner.settings") }}', icon: 'bi-gear' }
    ];

    function renderResults(query) {
        qsResults.innerHTML = '';
        if (!query) {
            qsDropdown.classList.add('d-none');
            return;
        }

        const lowerQuery = query.toLowerCase();
        const matches = searchDictionary.filter(item => 
            item.title.toLowerCase().includes(lowerQuery) || 
            item.keywords.includes(lowerQuery)
        );

        if (matches.length === 0) {
            qsResults.innerHTML = '<div class="px-3 py-3 text-muted text-center small">No features found for "'+query+'"</div>';
        } else {
            matches.forEach(match => {
                const link = document.createElement('a');
                link.href = match.url;
                link.className = 'd-flex align-items-center gap-3 px-3 py-2 text-decoration-none text-dark hover-bg-light';
                link.style.borderBottom = '1px solid #f8fafc';
                link.innerHTML = `
                    <div class="hz-icon-btn-light bg-light text-primary" style="width: 32px; height: 32px; font-size: 0.9rem;">
                        <i class="bi ${match.icon}"></i>
                    </div>
                    <div class="fw-bold" style="font-size: 0.85rem;">${match.title}</div>
                `;
                // Add hover style dynamically
                link.addEventListener('mouseenter', () => link.style.background = '#f1f5f9');
                link.addEventListener('mouseleave', () => link.style.background = 'transparent');
                
                qsResults.appendChild(link);
            });
        }
        qsDropdown.classList.remove('d-none');
    }

    qsInput.addEventListener('input', (e) => {
        renderResults(e.target.value.trim());
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!qsInput.contains(e.target) && !qsDropdown.contains(e.target)) {
            qsDropdown.classList.add('d-none');
        }
    });
    
    // Reopen on focus if there's text
    qsInput.addEventListener('focus', (e) => {
        if(e.target.value.trim().length > 0) {
            qsDropdown.classList.remove('d-none');
        }
    });
</script>

<script>
    // Close sidebar if user clicks outside of it on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.hz-sidebar');
        const toggleBtn = document.querySelector('.hz-mobile-toggle');
        if (window.innerWidth <= 991) {
            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

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
