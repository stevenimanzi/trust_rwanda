@extends('layouts.vendor')

@section('title', 'Store Settings')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 24px;
        border: none;
        margin-bottom: 24px;
    }
    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--hz-text-main);
        margin-bottom: 20px;
    }
    
    .form-label-pro {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-control-pro {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--hz-text-main);
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .form-control-pro:focus {
        border-color: var(--hz-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        background: #ffffff;
    }

    #settingsMap { height: 350px; border-radius: 12px; border: 1px solid #e2e8f0; z-index: 1; }
    
    .avatar-box { 
        width: 100px; height: 100px; border-radius: 16px; object-fit: cover; 
        border: 4px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.05); 
    }

    .nav-settings-stack .nav-link { 
        color: #64748b; font-weight: 600; padding: 1rem 1.2rem; border-radius: 8px; 
        margin-bottom: 0.5rem; transition: all 0.2s ease; display: flex; align-items: center; gap: 12px; 
        background: transparent; border: 1px solid transparent; width: 100%; text-align: left;
    }
    .nav-settings-stack .nav-link:hover { background: #f8fafc; color: var(--hz-primary); }
    .nav-settings-stack .nav-link.active { 
        background: #f3e8ff; color: var(--hz-primary); border-color: transparent; 
        font-weight: 700;
    }
    
    .btn-ecom {
        background: var(--hz-primary);
        color: white;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        border: none;
        transition: opacity 0.2s;
    }
    .btn-ecom:hover {
        opacity: 0.9;
        color: white;
    }
    
    @media (max-width: 991px) {
        .desktop-nav { display: none !important; }
    }
</style>
@endsection

@section('content')
@php
    $logoUrl = $vendor->shop_logo 
        ? asset('assets/uploads/logos/' . $vendor->shop_logo) 
        : 'https://ui-avatars.com/api/?name='.urlencode($displayShopName).'&background=8b5cf6&color=fff&bold=true';
@endphp
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="chart-title fs-4 mb-0">Store Settings</div>
</div>

@if (session('msg'))
    <div class="alert alert-success shadow-sm rounded-3 mb-4 border-0" style="background: #ecfdf5; color: #10b981;"><i class="bi bi-check-circle me-2"></i>{{ session('msg') }}</div>
@elseif (session('error'))
    <div class="alert alert-danger shadow-sm rounded-3 mb-4 border-0" style="background: #fff1f2; color: #f43f5e;"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="row g-4">
    <!-- Left Nav Panel -->
    <div class="col-lg-3">
        <div class="ecom-card text-center mb-4 pt-5 pb-4">
            <div class="position-relative d-inline-block">
                <img src="{{ $logoUrl }}" class="avatar-box" id="logoPreview">
                <label class="btn btn-sm position-absolute bottom-0 end-0 shadow-sm d-flex align-items-center justify-content-center" style="width:32px; height:32px; transform: translate(25%, 25%); cursor:pointer; background:var(--hz-primary); color:white; border:2px solid white; border-radius:50%;">
                    <i class="bi bi-camera-fill" style="font-size: 0.8rem;"></i>
                    <input type="file" form="profileForm" name="shop_logo" class="d-none" onchange="handleLogo(this)">
                </label>
            </div>
            <h6 class="fw-bold mt-4 mb-1 text-dark">{{ $displayShopName }}</h6>
            <div class="text-muted small">Vendor Account</div>
        </div>

        <!-- Tab Stack selectors -->
        <div class="desktop-nav">
            <div class="nav flex-column nav-settings-stack" id="v-pills-tab" role="tablist">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#profile-node" role="tab"><i class="bi bi-person-fill"></i> Store Details</button>
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#location-node" role="tab"><i class="bi bi-geo-alt-fill"></i> Location</button>
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#security-node" role="tab"><i class="bi bi-shield-lock-fill"></i> Security</button>
            </div>
        </div>

        <div class="d-lg-none mb-4">
            <select class="form-select form-control-pro py-2.5" onchange="triggerTab(this.value)">
                <option value="#profile-node">Store Details</option>
                <option value="#location-node">Location</option>
                <option value="#security-node">Security</option>
            </select>
        </div>
    </div>

    <!-- Right Forms Panel -->
    <div class="col-lg-9">
        <div class="tab-content">
            <!-- Tab 1: Profile specs -->
            <div class="tab-pane fade show active" id="profile-node" role="tabpanel">
                <form id="profileForm" method="POST" action="{{ route('vendor.settings.profile') }}" enctype="multipart/form-data" class="m-0 p-0">
                    @csrf
                    <div class="ecom-card">
                        <div class="chart-title">Identity & Contact Info</div>
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label-pro">Official Shop Name</label>
                                <input type="text" name="shop_name" class="form-control form-control-pro w-100" value="{{ old('shop_name', $vendor->shop_name) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label-pro">Public Description</label>
                                <textarea name="shop_description" class="form-control form-control-pro w-100" rows="5" placeholder="Tell buyers about your shop's features, catalogs, or response hours...">{{ old('shop_description', $vendor->shop_description) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Merchant Contact Name</label>
                                <input type="text" name="full_name" class="form-control form-control-pro w-100" value="{{ old('full_name', $vendor->full_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Mobile Money Phone</label>
                                <input type="text" name="phone" class="form-control form-control-pro w-100" value="{{ old('phone', $vendor->phone) }}" required>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 text-end border-top">
                            <button type="submit" class="btn-ecom">Save Identity Details</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab 2: Map Location -->
            <div class="tab-pane fade" id="location-node" role="tabpanel">
                <form method="POST" action="{{ route('vendor.settings.profile') }}" class="m-0 p-0">
                    @csrf
                    <div class="ecom-card">
                        <div class="chart-title">Store Geography</div>
                        
                        <div class="mb-4">
                            <label class="form-label-pro">Detailed Physical Address</label>
                            <input type="text" name="address" class="form-control form-control-pro w-100" value="{{ old('address', $vendor->address) }}" placeholder="e.g. KG 11 Ave, Kigali">
                        </div>
                        
                        <label class="form-label-pro mb-3">Map Coordinates (Pin your store)</label>
                        <div id="settingsMap" class="mb-4"></div>

                        <input type="hidden" name="latitude" id="input_lat" value="{{ old('latitude', $vendor->latitude ?? -1.9441) }}">
                        <input type="hidden" name="longitude" id="input_lng" value="{{ old('longitude', $vendor->longitude ?? 30.0619) }}">
                        
                        <div class="mt-2 pt-4 text-end border-top">
                            <button type="submit" class="btn-ecom">Update Location</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab 3: Account Security -->
            <div class="tab-pane fade" id="security-node" role="tabpanel">
                <form method="POST" action="{{ route('vendor.settings.security') }}" class="m-0 p-0">
                    @csrf
                    <div class="ecom-card">
                        <div class="chart-title">Account Security</div>
                        
                        <div class="mb-4">
                            <label class="form-label-pro">Current Email</label>
                            <input type="email" class="form-control form-control-pro w-100" value="{{ $vendor->email }}" disabled style="background:#f8fafc; color:#94a3b8; border-color:#f1f5f9; cursor:not-allowed;">
                            <div class="form-text mt-2 small text-muted"><i class="bi bi-info-circle me-1"></i> Contact system administration to transfer account email.</div>
                        </div>

                        <hr style="border-color:#f1f5f9; margin: 2rem 0;">

                        <div class="chart-title fs-6">Change Password</div>

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label-pro">Current Password</label>
                                <input type="password" name="current_password" class="form-control form-control-pro w-100" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">New Password</label>
                                <input type="password" name="password" class="form-control form-control-pro w-100" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-pro w-100" required>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 text-end border-top">
                            <button type="submit" class="btn-ecom">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Tab switching for mobile dropdown
    function triggerTab(targetId) {
        let triggerEl = document.querySelector(`.nav-settings-stack button[data-bs-target="${targetId}"]`);
        if(triggerEl) {
            let tabNode = new bootstrap.Tab(triggerEl);
            tabNode.show();
        }
    }

    // Logo instant preview
    function handleLogo(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Leaflet map init (delayed for tab transition)
    document.addEventListener("DOMContentLoaded", function() {
        let initialLat = {{ old('latitude', $vendor->latitude ?? -1.9441) }};
        let initialLng = {{ old('longitude', $vendor->longitude ?? 30.0619) }};
        let mymap = null;
        let marker = null;

        function renderMap() {
            if(mymap) return; // Prevent re-init
            
            mymap = L.map('settingsMap').setView([initialLat, initialLng], 12);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(mymap);

            let customIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            marker = L.marker([initialLat, initialLng], {icon: customIcon, draggable: true}).addTo(mymap);
            
            marker.on('dragend', function(e) {
                let coords = e.target.getLatLng();
                document.getElementById('input_lat').value = coords.lat;
                document.getElementById('input_lng').value = coords.lng;
            });

            mymap.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('input_lat').value = e.latlng.lat;
                document.getElementById('input_lng').value = e.latlng.lng;
            });
        }

        // Initialize if tab is active on load
        if(document.getElementById('location-node').classList.contains('active')) {
            renderMap();
        }

        // Initialize when tab is shown
        document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(btn => {
            btn.addEventListener('shown.bs.tab', function (e) {
                if(e.target.getAttribute('data-bs-target') === '#location-node') {
                    renderMap();
                    mymap.invalidateSize();
                }
            });
        });
    });
</script>
@endsection
