@extends('layouts.app')

@section('title', 'Nearby Shops - Trust Rwanda')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
<style>
    :root { --tr-indigo: #4F46E5; --tr-emerald: #10B981; }
    
    .ns-layout {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 80px);
        width: 100%;
        margin: 0;
    }
    .ns-list-container {
        background: #F8FAFC;
        overflow-y: auto;
    }
    
    #nearbyMap { 
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    @media (min-width: 992px) {
        .ns-layout {
            flex-direction: row;
        }
        .ns-list-container {
            width: 45%;
            padding: 30px 40px;
            border-right: 1px solid #e2e8f0;
        }
        .ns-map-container {
            width: 55%;
            height: 100%;
            position: sticky;
            top: 80px;
        }
    }
    @media (max-width: 991px) {
        .ns-list-container {
            order: 2;
            padding: 20px;
            flex-grow: 1;
        }
        .ns-map-container {
            order: 1;
            height: 45vh;
            width: 100%;
            flex-shrink: 0;
        }
    }

    .ns-filter-wrapper {
        position: relative;
        margin-bottom: 20px;
    }
    .ns-filter-scroll {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        padding-bottom: 10px;
        scrollbar-width: none;
        scroll-behavior: smooth;
    }
    .ns-scroll-btn {
        position: absolute;
        top: calc(50% - 5px);
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: white;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        color: #475569;
        transition: 0.3s;
    }
    .ns-scroll-btn:hover {
        background: var(--tr-indigo);
        color: white;
        border-color: var(--tr-indigo);
    }
    .ns-scroll-left { left: -10px; }
    .ns-scroll-right { right: -10px; }
    @media (max-width: 768px) {
        .ns-scroll-btn { display: none; }
    }
    .ns-filter-scroll::-webkit-scrollbar {
        display: none;
    }
    .ns-cat-btn {
        white-space: nowrap;
        border-radius: 20px;
        padding: 8px 16px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        color: #475569;
        text-decoration: none;
        background: white;
        transition: all 0.3s ease;
    }
    .ns-cat-btn:hover, .ns-cat-btn.active {
        background: var(--tr-indigo);
        color: white;
        border-color: var(--tr-indigo);
    }
    
    .shop-card-pro {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 16px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border-left: 4px solid transparent;
    }
    .shop-card-pro:hover, .shop-card-pro.active-card {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
        border-left-color: var(--tr-indigo);
    }

    .user-marker-pulse {
        width: 20px; height: 20px; background: var(--tr-indigo);
        border: 3px solid white; border-radius: 50%;
        box-shadow: 0 0 0 rgba(79, 70, 229, 0.4); animation: ns-pulse 2s infinite;
    }
    @keyframes ns-pulse {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
</style>
@endsection

@section('content')
<div class="ns-layout">
    <div class="ns-list-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="fw-800 text-dark mb-0">Nearby <span class="text-primary">Shops</span></h3>
            <button class="btn btn-sm btn-light border rounded-pill px-3 fw-bold shadow-sm" onclick="findMe()">
                <i class="bi bi-cursor-fill text-primary"></i> Locate Me
            </button>
        </div>

        <div class="ns-filter-wrapper">
            <button class="ns-scroll-btn ns-scroll-left" onclick="document.getElementById('nsCatScroll').scrollBy({left: -200, behavior: 'smooth'})" aria-label="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="ns-filter-scroll" id="nsCatScroll">
                <a href="{{ route('nearby.shops', ['lat' => $userLat, 'lng' => $userLng, 'category' => 'all']) }}" class="ns-cat-btn {{ $selectedCat === 'all' ? 'active' : '' }}">All Categories</a>
                @foreach($activeCategories as $cat)
                    <a href="{{ route('nearby.shops', ['lat' => $userLat, 'lng' => $userLng, 'category' => $cat['slug']]) }}" class="ns-cat-btn {{ $selectedCat === $cat['slug'] ? 'active' : '' }}">
                        <i class="bi {{ $cat['icon'] ?? 'bi-tag' }} me-1"></i> {{ $cat['name'] }}
                    </a>
                @endforeach
            </div>
            <button class="ns-scroll-btn ns-scroll-right" onclick="document.getElementById('nsCatScroll').scrollBy({left: 200, behavior: 'smooth'})" aria-label="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        
        <div class="input-group bg-white rounded-pill border px-3 py-2 mb-4 shadow-sm">
            <i class="bi bi-search text-muted mt-1 me-2"></i>
            <input type="text" id="shopNameSearch" class="form-control border-0 bg-transparent shadow-none p-0" placeholder="Search shops by name..." onkeyup="filterShops()">
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 border-0 fw-bold">SCANNING: 15 KM</span>
            <small class="text-muted fw-bold">{{ count($nearbyShops) }} shops found</small>
        </div>

        <div id="shopList" class="pb-4">
            @if (count($nearbyShops) == 0)
                <div class="p-5 text-center text-muted bg-white rounded-4 border">
                    <i class="bi bi-shop fs-1 opacity-25"></i>
                    <p class="mt-2 fw-bold mb-0">No shops matched your criteria.</p>
                </div>
            @else
                @foreach ($nearbyShops as $shop)
                    <div class="shop-card-pro shop-list-item" 
                         id="shop-{{ $shop->id }}" 
                         data-name="{{ strtolower($shop->shop_name) }}"
                         onclick="focusShop({{ $shop->latitude }}, {{ $shop->longitude }}, {{ $shop->id }})">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $shop->shop_logo ? asset($shop->shop_logo) : 'https://ui-avatars.com/api/?name='.urlencode($shop->shop_name).'&background=4F46E5&color=fff' }}" 
                                 class="rounded-circle border flex-shrink-0" 
                                 style="width:60px;height:60px;object-fit:cover;" 
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->shop_name) }}&background=4F46E5&color=fff'">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-800 text-dark h6 mb-1 text-truncate">
                                    {{ $shop->shop_name }}
                                    @if($shop->is_verified)
                                        <i class="bi bi-patch-check-fill text-primary ms-1" title="Verified Shop"></i>
                                    @endif
                                </div>
                                <div class="small text-muted text-truncate mb-2"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $shop->address }}</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge rounded-pill bg-light text-dark border px-2 py-1"><i class="bi bi-geo"></i> {{ number_format($shop->distance, 1) }} km away</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    
    <div class="ns-map-container">
        <div id="nearbyMap"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>
<script>
let map, userMarker, radiusCircle;
let markers = [];
const nearbyShops = @json($nearbyShops);

function initMap() {
    try {
        const lat = {{ $userLat }};
        const lng = {{ $userLng }};

        if (typeof L === 'undefined') {
            throw new Error('Leaflet library not loaded');
        }

        map = L.map('nearbyMap').setView([lat, lng], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const pulseIcon = L.divIcon({ className: 'user-marker-pulse', iconSize: [20, 20] });
        userMarker = L.marker([lat, lng], { icon: pulseIcon }).addTo(map);

        radiusCircle = L.circle([lat, lng], {
            color: '#4F46E5', fillColor: '#4F46E5', fillOpacity: 0.1, weight: 2, dashArray: '10, 10', radius: 15000 
        }).addTo(map);

        renderShopsOnMap();

        setTimeout(() => {
            if (map) {
                map.invalidateSize();
            }
        }, 100);
    } catch (error) {
        console.error('Map initialization failed:', error);
        const mapEl = document.getElementById('nearbyMap');
        if (mapEl) {
            mapEl.innerHTML = '<div class="text-center p-5"><i class="bi bi-exclamation-triangle fs-1 text-warning"></i><p class="mt-3">Map failed to load: ' + error.message + '</p></div>';
        }
    }
}

function renderShopsOnMap() {
    nearbyShops.forEach((shop) => {
        const logoUrl = shop.shop_logo ? `/${shop.shop_logo}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(shop.shop_name)}&background=4F46E5&color=fff`;
        const marker = L.marker([shop.latitude, shop.longitude]).addTo(map)
            .bindPopup(`
                <div class="text-center p-2">
                    <img src="${logoUrl}" class="rounded mb-2" style="width:40px;height:40px;object-fit:cover;">
                    <h6 class="m-0 fw-800">${shop.shop_name}</h6>
                    <a href="/shop/${shop.id}" class="btn btn-primary btn-sm rounded-pill w-100 mt-2 fw-bold">Visit Shop</a>
                </div>`);
        markers.push(marker);
    });
    
    if(nearbyShops.length > 0) {
        map.fitBounds(radiusCircle.getBounds(), { padding: [30, 30] });
    }
}

function findMe() {
    if (!navigator.geolocation) return alert("GPS not available.");
    navigator.geolocation.getCurrentPosition((pos) => {
        window.location.href = `{{ route('nearby.shops') }}?lat=${pos.coords.latitude}&lng=${pos.coords.longitude}&category={{ $selectedCat }}`;
    });
}

function filterShops() {
    const query = document.getElementById('shopNameSearch').value.toLowerCase();
    document.querySelectorAll('.shop-list-item').forEach(item => {
        item.style.display = item.getAttribute('data-name').includes(query) ? 'block' : 'none';
    });
}

function focusShop(lat, lng, id) {
    document.querySelectorAll('.shop-card-pro').forEach(c => c.classList.remove('active-card'));
    const card = document.getElementById(`shop-${id}`);
    if(card) card.classList.add('active-card');
    map.flyTo([lat, lng], 16, { duration: 1.5 });
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('nearbyMap')) {
        initMap();
    }
});
</script>
@endsection
