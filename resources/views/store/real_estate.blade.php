@extends('layouts.app')
@section('content')
@php
$currentLang = app()->getLocale();
$reLang = [
    'rw' => [
        'title' => 'Gushakisha Inzu n\'Ibibanza mu Rwanda',
        'placeholder_search' => 'Shakisha Kibagabaga, Nyarutarama, Rebero...',
        'any_status' => 'Icyiciro Byose',
        'for_rent' => 'Ibikodeshwa',
        'for_sale' => 'Ibigurishwa',
        'any_type' => 'Ubwoko Byose',
        'any_price' => 'Ibiciro Byose',
        'price_under_500k' => 'Munsi y\'ibihumbi 500 RWF',
        'price_500k_2m' => '500k - 2M RWF',
        'price_2m_10m' => '2M - 10M RWF',
        'price_10m_plus' => 'Hejuruy\'i 10M RWF',
        'any_beds' => 'Ibyumba Byose',
        'any_baths' => 'Ubwiherero Byose',
        'btn_map' => 'Aho ndi',
        'view_details' => 'Reba Ibisobanuro',
        'beds_label' => 'Ibyumba',
        'baths_label' => 'Ubwiherero',
        'sqm_label' => 'sqm',
        'verified' => 'Byemejwe',
        'list_cta' => 'Ufite Inzu cyangwa Ikibanza?',
        'list_sub' => 'Kora lisiti y\'imitungo yawe kuri Trust Rwanda maze uziheze ku bakiriya benshi ku buntu!',
        'list_btn' => 'Kwiyandikisha nk\'Umunyamutungo',
        'no_results' => 'Nta mitungo yabonetse ihuje n\'ibyo mwashakishije.',
        'show_grid' => 'Imitungo Yose',
        'show_map' => 'Reba ku Ikarita'
    ],
    'en' => [
        'title' => 'Trust Rwanda Premium Real Estate',
        'placeholder_search' => 'Search by Kibagabaga, Nyarutarama, Rebero, Kiyovu...',
        'any_status' => 'Any Status',
        'for_rent' => 'For Rent',
        'for_sale' => 'For Sale',
        'any_type' => 'Any Type',
        'any_price' => 'Any Price',
        'price_under_500k' => 'Under 500k RWF',
        'price_500k_2m' => '500k - 2M RWF',
        'price_2m_10m' => '2M - 10M RWF',
        'price_10m_plus' => 'Above 10M RWF',
        'any_beds' => 'Any Beds',
        'any_baths' => 'Any Baths',
        'btn_map' => 'Locate Area',
        'view_details' => 'View Details',
        'beds_label' => 'Beds',
        'baths_label' => 'Baths',
        'sqm_label' => 'sqm',
        'verified' => 'Verified',
        'list_cta' => 'Own a House or Land?',
        'list_sub' => 'Partner with Trust Rwanda and list your properties to reach thousands of buyers for free!',
        'list_btn' => 'Become a Property Partner',
        'no_results' => 'No properties found matching your criteria.'
    ],
    'sw' => [
        'title' => 'Kutafuta Nyumba na Ardhi Rwanda',
        'placeholder_search' => 'Tafuta kwa Kibagabaga, Nyarutarama, Rebero...',
        'any_status' => 'Hali Yoyote',
        'for_rent' => 'Ya Kupanga',
        'for_sale' => 'Ya Kuuza',
        'any_type' => 'Aina Yoyote',
        'any_price' => 'Bei Yoyote',
        'price_under_500k' => 'Chini ya 500k RWF',
        'price_500k_2m' => '500k - 2M RWF',
        'price_2m_10m' => '2M - 10M RWF',
        'price_10m_plus' => 'Zaidi ya 10M RWF',
        'any_beds' => 'Vyumba Vyote',
        'any_baths' => 'Bafu Zote',
        'btn_map' => 'Tafuta Eneo',
        'view_details' => 'Angalia Maelezo',
        'beds_label' => 'Vyumba',
        'baths_label' => 'Bafu',
        'sqm_label' => 'sqm',
        'verified' => 'Imethibitishwa',
        'list_cta' => 'Je, unamiliki nyumba au ardhi?',
        'list_sub' => 'Weka orodha yako kwenye Trust Rwanda sasa na ufikie maelfu ya wateja bure kabisa!',
        'list_btn' => 'Jisajili kama Mshirika',
        'no_results' => 'Hakuna mali iliyopatikana kulingana na vigezo vyako.'
    ]
];
$t = $reLang[$currentLang] ?? $reLang['en'];
@endphp
@push('styles')
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css' />
@endpush
@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js'></script>
@endpush

<style>
    body { background-color: #f8fafc; }
    
    .re-layout {
        width: 100%;
        min-height: calc(100vh - 120px);
        margin: 0;
        position: relative;
    }
    .re-filter-bar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e8f0;
        z-index: 100;
        position: relative;
    }
    
    /* Default Full-Width Grid Layout */
    .re-list-container {
        width: 100%;
        background: #f8fafc;
        padding: 35px 0;
    }
    .re-map-container {
        display: none; /* Hidden by default in grid view */
        width: 100%;
        height: 100%;
    }
    
    /* Toggled Split-View Layout (Active when user clicks Map view or Locates Area) */
    .re-layout.split-view {
        display: flex;
        flex-direction: column;
    }
    @media (min-width: 992px) {
        .re-layout.split-view {
            flex-direction: row;
            height: calc(100vh - 170px);
            overflow: hidden;
        }
        .re-layout.split-view .re-list-container {
            width: 45%;
            height: 100%;
            overflow-y: auto;
            padding: 25px 30px;
            border-right: 1px solid #e2e8f0;
        }
        .re-layout.split-view .re-map-container {
            display: block; /* Show side-by-side */
            width: 55%;
            height: 100%;
            position: sticky;
            top: 0;
        }
    }
    @media (max-width: 991px) {
        .re-layout.split-view .re-list-container {
            order: 2;
            width: 100%;
            padding: 20px;
        }
        .re-layout.split-view .re-map-container {
            display: block; /* Show stacked on top */
            order: 1;
            height: 45vh;
            width: 100%;
        }
    }

    #reMap {
        width: 100%;
        height: 100%;
        z-index: 1;
    }
    
    /* Toggle switch style */
    .view-toggle-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 50px;
        color: #475569;
        transition: 0.2s;
        font-size: 0.8rem;
    }
    .view-toggle-btn.active {
        background: #6366f1;
        color: white !important;
        border-color: #6366f1;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);
    }
    
    .prop-card-custom {
        background: white;
        border-radius: 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.03);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }
    .prop-card-custom:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.08);
        border-color: rgba(99, 102, 241, 0.15);
    }
    .prop-badge-status {
        position: absolute;
        top: 15px; left: 15px;
        color: white;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 50px;
        text-transform: uppercase;
        z-index: 5;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        letter-spacing: 0.5px;
    }
    .prop-badge-status.rent { background: #3b82f6; }
    .prop-badge-status.sale { background: #10b981; }
    
    .video-tour-badge {
        position: absolute;
        top: 15px; right: 15px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 50px;
        z-index: 5;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .prop-img-box {
        height: 200px;
        overflow: hidden;
        background: #e2e8f0;
        position: relative;
    }
    .prop-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .prop-card-custom:hover .prop-img-box img {
        transform: scale(1.06);
    }
    .prop-info-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .prop-loc-row {
        font-size: 0.7rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .prop-title {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.4;
        margin-bottom: 10px;
        text-decoration: none !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 44px;
        transition: color 0.2s;
    }
    .prop-title:hover {
        color: #6366f1;
    }
    .prop-specs {
        display: flex;
        gap: 6px;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.7rem;
        color: #475569;
        font-weight: 700;
        flex-wrap: wrap;
    }
    .prop-specs span {
        display: flex;
        align-items: center;
        gap: 4px;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 50px;
    }
    .prop-specs i {
        color: #6366f1;
    }
    .prop-price-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
    }
    .prop-price-val {
        font-size: 1.15rem;
        font-weight: 900;
        color: #0f172a;
    }
    .prop-price-subtext {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
    }
    .prop-actions-row {
        display: flex;
        gap: 6px;
        width: 100%;
        margin-top: 12px;
        flex-wrap: nowrap;
        align-items: center;
    }
    .btn-prop-primary {
        flex: 1;
        background: #6366f1;
        color: white !important;
        border: none;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 9px 10px;
        text-align: center;
        text-decoration: none !important;
        transition: 0.2s;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btn-prop-primary:hover {
        background: #4f46e5;
    }
    .btn-prop-map {
        background: #f1f5f9;
        color: #475569 !important;
        border: 1px solid #cbd5e1;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 9px 12px;
        text-align: center;
        text-decoration: none !important;
        transition: 0.2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .btn-prop-map:hover {
        background: #e2e8f0;
    }
    .btn-prop-wa {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f0fdf4;
        color: #10b981 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(16, 185, 129, 0.15);
        text-decoration: none !important;
        transition: 0.2s;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .btn-prop-wa:hover {
        background: #10b981;
        color: white !important;
    }
    
    /* Premium Map price tags */
    .custom-price-marker {
        background: transparent;
        border: none;
    }
    .price-marker-inner {
        background: white;
        color: #0f172a;
        font-weight: 800;
        padding: 5px 10px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.12);
        white-space: nowrap;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
        font-size: 0.78rem;
        position: relative;
    }
    .price-marker-inner::after {
        content: ''; position: absolute; bottom: -5px; left: 50%;
        transform: translateX(-50%); border-width: 5px 5px 0;
        border-style: solid; border-color: white transparent transparent transparent;
        display: block; width: 0;
    }
    .price-marker-inner:hover, .price-marker-inner.active-marker {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
        transform: scale(1.1);
        z-index: 999;
    }
    .price-marker-inner:hover::after, .price-marker-inner.active-marker::after {
        border-color: #6366f1 transparent transparent transparent;
    }
    .user-marker-pulse {
        width: 18px; height: 18px; background: #6366f1;
        border: 3px solid white; border-radius: 50%;
        box-shadow: 0 0 0 rgba(99, 102, 241, 0.4); animation: map-pulse 2s infinite;
    }
    @keyframes map-pulse {
        0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.6); }
        70% { box-shadow: 0 0 0 12px rgba(99, 102, 241, 0); }
        100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
    }
    
    .pulse-highlight {
        animation: card-glow 1.5s ease;
    }
    @keyframes card-glow {
        0%, 100% { border-color: #f1f5f9; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.03); }
        50% { border-color: #6366f1; box-shadow: 0 0 20px rgba(99, 102, 241, 0.25); }
    }

    .partner-cta-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: white;
        border-radius: 18px;
        padding: 30px;
        margin-top: 30px;
        position: relative;
        overflow: hidden;
    }
    .partner-cta-card::after {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(255,255,255,0.06) 0%, transparent 60%);
    }
</style>

<style>
    /* Premium Filter Bar Styles */
    .re-filter-wrapper {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 15px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    
    .re-filter-bar {
        background: transparent;
        z-index: 100;
        position: relative;
    }
    
    .filter-item {
        position: relative;
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        padding: 6px 16px;
        transition: all 0.3s ease;
    }

    .filter-item:hover, .filter-item:focus-within {
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
    }
    
    .filter-icon {
        color: #64748b;
        font-size: 14px;
        margin-right: 8px;
        transition: color 0.3s ease;
    }
    
    .filter-item:focus-within .filter-icon {
        color: #6366f1;
    }

    .filter-input {
        border: none;
        background: transparent;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        width: 100%;
        outline: none;
        padding: 4px 0;
        box-shadow: none !important;
    }
    
    .filter-select {
        border: none;
        background: transparent;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        width: 100%;
        outline: none;
        padding: 4px 0;
        appearance: none;
        cursor: pointer;
        box-shadow: none !important;
    }
    
    .filter-select-wrapper::after {
        content: '\F282';
        font-family: 'bootstrap-icons';
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 10px;
        pointer-events: none;
    }

    .filter-input::placeholder {
        color: #94a3b8;
        font-weight: 500;
    }
</style>

<!-- Full-width Filters Bar -->
<div class="re-filter-wrapper">
    <div class="re-filter-bar container">
        <form id="reFilterForm" class="d-flex flex-wrap gap-2 align-items-center w-100" onsubmit="event.preventDefault();">
            <!-- Text Search -->
            <div class="flex-grow-1" style="min-width: 250px;">
                <div class="filter-item">
                    <i class="bi bi-search filter-icon"></i>
                    <input type="text" id="propSearch" class="filter-input" placeholder="{{ $t['placeholder_search'] }}" onkeyup="runFilters()">
                </div>
            </div>
            
            <!-- Rent/Sale Select -->
            <div style="min-width: 140px;">
                <div class="filter-item filter-select-wrapper">
                    <i class="bi bi-tag filter-icon"></i>
                    <select id="filterStatus" class="filter-select" onchange="runFilters()">
                        <option value="">{{ $t['any_status'] }}</option>
                        <option value="rent">{{ $t['for_rent'] }}</option>
                        <option value="sale">{{ $t['for_sale'] }}</option>
                    </select>
                </div>
            </div>

            <!-- Property Type Select -->
            <div style="min-width: 140px;">
                <div class="filter-item filter-select-wrapper">
                    <i class="bi bi-building filter-icon"></i>
                    <select id="filterCategory" class="filter-select" onchange="runFilters()">
                        <option value="">{{ $t['any_type'] }}</option>
                        <option value="house">House</option>
                        <option value="apartment">Apartment</option>
                        <option value="land">Land</option>
                        <option value="commercial">Commercial</option>
                    </select>
                </div>
            </div>

            <!-- Price Select -->
            <div style="min-width: 140px;">
                <div class="filter-item filter-select-wrapper">
                    <i class="bi bi-cash filter-icon"></i>
                    <select id="filterPrice" class="filter-select" onchange="runFilters()">
                        <option value="">{{ $t['any_price'] }}</option>
                        <option value="0-500k">{{ $t['price_under_500k'] }}</option>
                        <option value="500k-2m">{{ $t['price_500k_2m'] }}</option>
                        <option value="2m-10m">{{ $t['price_2m_10m'] }}</option>
                        <option value="10m+">{{ $t['price_10m_plus'] }}</option>
                    </select>
                </div>
            </div>

            <!-- Beds Select -->
            <div style="min-width: 140px;">
                <div class="filter-item filter-select-wrapper">
                    <i class="bi bi-door-open filter-icon"></i>
                    <select id="filterBeds" class="filter-select" onchange="runFilters()">
                        <option value="">{{ $t['any_beds'] }}</option>
                        <option value="1">1+ {{ $t['beds_label'] }}</option>
                        <option value="2">2+ {{ $t['beds_label'] }}</option>
                        <option value="3">3+ {{ $t['beds_label'] }}</option>
                        <option value="4">4+ {{ $t['beds_label'] }}</option>
                    </select>
                </div>
            </div>

            <!-- Toggle View Switch (Grid / Map) -->
            <div class="ms-auto d-flex gap-1">
                <button type="button" id="toggleGridBtn" class="view-toggle-btn active" onclick="switchView('grid')">
                    <i class="bi bi-grid-fill"></i>
                </button>
                <button type="button" id="toggleMapBtn" class="view-toggle-btn" onclick="switchView('map')">
                    <i class="bi bi-map-fill"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Layout container -->
<div class="re-layout container py-5" id="reLayoutContainer">
    <!-- Left list column -->
    <div class="re-list-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-900 text-dark mb-0"><i class="bi bi-houses me-1 text-primary"></i> {{ $t['title'] }}</h5>
            <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold" onclick="locateMe(this)" style="font-size:0.75rem;">
                <i class="bi bi-cursor-fill text-warning me-1"></i> {{ $t['btn_map'] }}
            </button>
        </div>
        
        <!-- Listings grid -->
        <div class="row g-4" id="propertiesListGrid">
            <!-- Dynamically populated client-side -->
        </div>

        <!-- Become a partner card -->
        <div class="partner-cta-card">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h5 class="fw-bold mb-2">{{ $t['list_cta'] }}</h5>
                    <p class="text-white-50 small mb-0">{{ $t['list_sub'] }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('property_owner.register') }}" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold" style="color:#1e1b4b; font-size:0.8rem;">{{ $t['list_btn'] }}</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Map column -->
    <div class="re-map-container">
        <div id="reMap"></div>
    </div>
</div>

<script>
    const mapProperties = @json(array_values($propertiesList));
    const langTrans = @json($t);
    const defaultLat = {{ $lat }};
    const defaultLng = {{ $lng }};
    
    let map;
    let markers = [];
    let markersById = {};
    let activeProperties = [...mapProperties];
    let currentViewMode = 'grid'; // 'grid' or 'map'

    // Helper for formatting prices in Kigali tags
    function formatShortPrice(num) {
        if (isNaN(num)) return num;
        if (num >= 1000000) return (num / 1000000).toFixed(1).replace('.0', '') + 'M';
        if (num >= 1000) return (num / 1000).toFixed(1).replace('.0', '') + 'K';
        return num;
    }

    // Toggle view handler between full-width grid and split screen map view
    function switchView(mode) {
        currentViewMode = mode;
        const layoutEl = document.getElementById("reLayoutContainer");
        const gridBtn = document.getElementById("toggleGridBtn");
        const mapBtn = document.getElementById("toggleMapBtn");

        if (mode === 'grid') {
            layoutEl.classList.remove("split-view");
            gridBtn.classList.add("active");
            mapBtn.classList.remove("active");
        } else {
            layoutEl.classList.add("split-view");
            gridBtn.classList.remove("active");
            mapBtn.classList.add("active");
            
            // Lazy initialize Leaflet map only when split view is first shown
            if (!map) {
                initMap(defaultLat, defaultLng);
            } else {
                setTimeout(() => {
                    map.invalidateSize();
                    renderMapMarkers();
                }, 100);
            }
        }
        
        // Re-render list items to change card columns count dynamically (3 columns in grid, 2 columns in map)
        renderListItems();
    }

    // Initialize Map Area
    function initMap(lat, lng, zoom = 12) {
        try {
            if (map) {
                map.setView([lat, lng], zoom);
                return;
            }
            
            map = L.map('reMap').setView([lat, lng], zoom);
            
            // 2D Street View Layer
            const streetTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
            });

            // 3D Satellite Layer
            const satelliteTiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });

            // Satellite Hybrid Labels Layer
            const hybridLabels = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; CARTO'
            });

            const satelliteGroup = L.layerGroup([satelliteTiles, hybridLabels]);

            // Add default 2D street layer
            streetTiles.addTo(map);

            // Layer Toggle Control
            const baseMaps = {
                "2D Street Map": streetTiles,
                "3D Satellite View": satelliteGroup
            };

            L.control.layers(baseMaps, null, { collapsed: false, position: 'topright' }).addTo(map);

            // Add User Location Pulse Icon
            const pulseIcon = L.divIcon({ className: 'user-marker-pulse', iconSize: [18, 18] });
            L.marker([lat, lng], { icon: pulseIcon }).addTo(map);

            // Add 30km Radius Circle boundary
            const radiusCircle = L.circle([lat, lng], {
                color: '#6366f1', fillColor: '#6366f1', fillOpacity: 0.05, weight: 1.5, dashArray: '8, 8', radius: 30000 
            }).addTo(map);

            renderMapMarkers();
            
            if (activeProperties.length > 0) {
                map.fitBounds(radiusCircle.getBounds(), { padding: [20, 20] });
            }
        } catch (error) {
            console.error('Map creation failed:', error);
        }
    }

    // Render Markers on Map dynamically
    function renderMapMarkers() {
        if (!map) return;
        
        // Clear previous markers
        markers.forEach(m => map.removeLayer(m));
        markers = [];
        markersById = {};
        
        activeProperties.forEach(prop => {
            if (prop.latitude && prop.longitude) {
                const markerIcon = L.divIcon({
                    className: 'custom-price-marker',
                    html: `<div class="price-marker-inner" id="marker-inner-${prop.id}" style="background:#6366f1; color:white; border-color:#6366f1; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; box-shadow:0 4px 15px rgba(99,102,241,0.45); border: 2px solid white;"><i class="bi bi-house-door-fill"></i></div>`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 36],
                    popupAnchor: [0, -36]
                });
                
                const marker = L.marker([prop.latitude, prop.longitude], {icon: markerIcon}).addTo(map);
                
                const popupContent = `
                    <div style="text-align: center; max-width: 200px; padding: 4px; font-family: sans-serif;">
                        <img src="${prop.image_url}" style="width: 100%; height: 95px; object-fit: cover; border-radius: 8px; margin-bottom: 6px;">
                        <h6 style="margin: 0 0 4px 0; font-size: 11px; font-weight: 800; color: #0f172a; line-height: 1.3;">${prop.title}</h6>
                        <p style="margin: 0 0 8px 0; color: #64748b; font-size: 9px;"><i class="bi bi-geo-alt-fill text-danger me-1"></i>${prop.address}</p>
                        <div style="display: flex; gap: 4px; justify-content: center; align-items: center;">
                            <a href="/products/${prop.id}" style="flex: 1; background: #6366f1; color: white; text-decoration: none; padding: 6px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-align: center; white-space: nowrap;">${langTrans.view_details}</a>
                            <a href="https://wa.me/${prop.phone.replace(/[^0-9]/g, '')}?text=Hello, I am interested in your property listing: ${encodeURIComponent(prop.title)}" target="_blank" style="width: 26px; height: 26px; border-radius: 4px; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none;"><i class="bi bi-whatsapp" style="font-size:12px;"></i></a>
                        </div>
                    </div>
                `;
                
                marker.bindPopup(popupContent, { closeButton: false });
                
                // Clicking marker zooms and highlights list card
                marker.on('click', () => {
                    focusListCard(prop.id);
                });

                markers.push(marker);
                markersById[prop.id] = marker;
            }
        });
    }

    // Render list items dynamically
    function renderListItems() {
        const grid = document.getElementById("propertiesListGrid");
        grid.innerHTML = "";
        
        if (activeProperties.length === 0) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="benefit-icon" style="width:50px; height:50px; font-size:1.4rem;"><i class="bi bi-houses"></i></div>
                    <h6 class="fw-bold mt-3 text-dark">${langTrans.no_results}</h6>
                </div>
            `;
            return;
        }
        
        // Dynamically toggle column classes (3 columns in full-width grid, 2 columns in split-view map mode)
        const colClass = (currentViewMode === 'grid') ? 'col-lg-4 col-md-6 col-12' : 'col-lg-6 col-md-6 col-12';
        
        activeProperties.forEach(p => {
            const isRent = p.listing_type === 'rent';
            const pricePeriod = isRent ? '/ mo' : '';
            const badgeClass = isRent ? 'rent' : 'sale';
            const badgeText = isRent ? langTrans.for_rent : langTrans.for_sale;
            
            const cardHtml = `
                <div class="${colClass} mb-3 prop-card-item-col" id="prop-item-card-${p.id}" data-id="${p.id}" onmouseenter="highlightMarker(${p.id})" onmouseleave="resetMarker(${p.id})">
                    <div class="prop-card-custom" onclick="focusPropertyOnMap(event, ${p.latitude}, ${p.longitude}, ${p.id})">
                        <span class="prop-badge-status ${badgeClass}">${badgeText}</span>
                        ${p.has_video ? `<span class="video-tour-badge"><i class="bi bi-play-btn-fill"></i> Video</span>` : ''}
                        
                        <div class="prop-img-box" onclick="window.location.href='/products/${p.id}'" style="cursor: pointer;">
                            <img src="${p.image_url}" onerror="this.src='https://placehold.co/400x250?text=Property';" alt="${p.title}">
                        </div>
                        <div class="prop-info-body">
                            <div class="prop-loc-row">
                                <i class="bi bi-geo-alt-fill text-danger"></i> ${p.address}
                            </div>
                            <a href="/products/${p.id}" class="prop-title" title="${p.title}">${p.title}</a>
                            
                            <div class="small text-muted mb-3 d-flex align-items-center gap-1" style="font-size: 0.72rem; font-weight: 700;">
                                <i class="bi bi-shop text-primary"></i>
                                <span>${p.shop_name}</span>
                                <span class="text-success small fw-bold"><i class="bi bi-patch-check-fill"></i></span>
                            </div>
                            
                            <div class="prop-specs">
                                ${p.beds > 0 ? `<span><i class="bi bi-door-open"></i> ${p.beds}</span>` : ''}
                                ${p.baths > 0 ? `<span><i class="bi bi-droplet"></i> ${p.baths}</span>` : ''}
                                <span><i class="bi bi-bounding-box-circles"></i> ${p.size_sqm} ${langTrans.sqm_label}</span>
                            </div>

                            <div class="prop-price-footer">
                                <div>
                                    <span class="prop-price-val">${new Intl.NumberFormat().format(p.price)} RWF</span>
                                    <span class="prop-price-subtext">${pricePeriod}</span>
                                </div>
                            </div>
                            
                            <div class="prop-actions-row">
                                <a href="/products/${p.id}" class="btn-prop-primary">${langTrans.view_details}</a>
                                <button type="button" class="btn-prop-map" onclick="viewOnMap(${p.latitude}, ${p.longitude}, ${p.id})">
                                    <i class="bi bi-geo-alt"></i>
                                </button>
                                <a href="https://wa.me/${p.phone.replace(/[^0-9]/g, '')}?text=Hello, I am interested in your property listing: ${encodeURIComponent(p.title)}" target="_blank" class="btn-prop-wa">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            grid.insertAdjacentHTML('beforeend', cardHtml);
        });
    }

    // Client-side dynamic search and multi-filtering logic
    function runFilters() {
        const query = document.getElementById("propSearch").value.toLowerCase();
        const status = document.getElementById("filterStatus").value;
        const category = document.getElementById("filterCategory").value;
        const priceRange = document.getElementById("filterPrice").value;
        const beds = document.getElementById("filterBeds").value;

        activeProperties = mapProperties.filter(p => {
            // Text search match
            if (query && !p.title.toLowerCase().includes(query) && !p.address.toLowerCase().includes(query) && !p.shop_name.toLowerCase().includes(query)) {
                return false;
            }
            // Status match
            if (status && p.listing_type !== status) {
                return false;
            }
            // Category match
            if (category && p.category !== category) {
                return false;
            }
            // Beds match
            if (beds && p.beds < parseInt(beds)) {
                return false;
            }
            // Price match
            if (priceRange) {
                if (priceRange === '0-500k' && p.price > 500000) return false;
                if (priceRange === '500k-2m' && (p.price < 500000 || p.price > 2000000)) return false;
                if (priceRange === '2m-10m' && (p.price < 2000000 || p.price > 10000000)) return false;
                if (priceRange === '10m+' && p.price < 10000000) return false;
            }
            return true;
        });

        renderListItems();
        renderMapMarkers();
    }

    // Map marker interactions
    function highlightMarker(id) {
        const markerInner = document.getElementById(`marker-inner-${id}`);
        if (markerInner) markerInner.classList.add('active-marker');
        if (markersById[id]) markersById[id].setZIndexOffset(1000);
    }
    
    function resetMarker(id) {
        const markerInner = document.getElementById(`marker-inner-${id}`);
        if (markerInner) markerInner.classList.remove('active-marker');
        if (markersById[id]) markersById[id].setZIndexOffset(0);
    }

    function focusPropertyOnMap(e, lat, lng, id) {
        if (e.target.closest('a') || e.target.closest('button') || e.target.closest('.prop-img-box')) return;
        if (currentViewMode === 'map' && map) {
            map.flyTo([lat, lng], 15, { duration: 1.2 });
            highlightMarker(id);
            if (markersById[id]) {
                markersById[id].openPopup();
            }
        }
    }

    // Card Map button click action
    function viewOnMap(lat, lng, id) {
        if (currentViewMode !== 'map') {
            switchView('map');
        }
        setTimeout(() => {
            if (map) {
                map.flyTo([lat, lng], 15, { duration: 1.2 });
                highlightMarker(id);
                if (markersById[id]) {
                    markersById[id].openPopup();
                    markersById[id].setZIndexOffset(1000);
                }
            }
        }, 200);
    }

    // List highlight scroll sync
    function focusListCard(id) {
        const cardCol = document.getElementById(`prop-item-card-${id}`);
        if (cardCol) {
            cardCol.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            const cardEl = cardCol.querySelector('.prop-card-custom');
            cardEl.classList.add('pulse-highlight');
            setTimeout(() => {
                cardEl.classList.remove('pulse-highlight');
            }, 1800);
        }
    }

    // Geolocation locateMe handler
    function locateMe(btn) {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Locating...';
        btn.disabled = true;
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const uLat = position.coords.latitude;
                const uLng = position.coords.longitude;
                if (currentViewMode !== 'map') {
                    switchView('map');
                } else {
                    initMap(uLat, uLng, 13);
                }
                
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, () => {
                alert("Location permission denied or unavailable. Centering on default Kigali location.");
                if (currentViewMode !== 'map') {
                    switchView('map');
                } else {
                    initMap(defaultLat, defaultLng);
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        } else {
            alert("Geolocation is not supported by this browser.");
            if (currentViewMode !== 'map') {
                switchView('map');
            } else {
                initMap(defaultLat, defaultLng);
            }
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    // On Load init
    document.addEventListener("DOMContentLoaded", () => {
        renderListItems();
    });
</script>


@endsection
