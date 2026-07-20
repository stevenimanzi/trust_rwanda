@extends('layouts.app')

@section('title', 'Property Owner Registration - Trust Rwanda')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    .tr-login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 200px);
        padding: 40px 20px;
        background-color: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .tr-login-container {
        width: 100%;
        max-width: 600px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        padding: 40px;
        box-sizing: border-box;
    }

    .tr-logo-area {
        text-align: center;
        margin-bottom: 25px;
    }

    .tr-logo-area i {
        font-size: 36px;
        color: #4f46e5;
    }

    .tr-title {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        text-align: center;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .tr-subtitle {
        color: #64748b;
        font-size: 14px;
        text-align: center;
        margin-bottom: 30px;
    }

    .tr-form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .tr-form-col {
        flex: 1;
    }

    .tr-form-group {
        margin-bottom: 20px;
    }

    .tr-form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .tr-input-wrapper {
        position: relative;
    }

    .tr-form-control {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        color: #0f172a;
        transition: all 0.2s ease;
        background: #fff;
        box-sizing: border-box;
    }

    .tr-form-control:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .tr-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        transition: color 0.2s;
    }

    .tr-form-control:focus + .tr-icon {
        color: #4f46e5;
    }

    .tr-btn-submit {
        width: 100%;
        padding: 14px;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 10px;
    }

    .tr-btn-submit:hover {
        background: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .tr-register-link {
        text-align: center;
        margin-top: 25px;
        color: #64748b;
        font-size: 14px;
    }

    .tr-register-link a {
        color: #4f46e5;
        font-weight: 700;
        text-decoration: none;
    }

    .tr-register-link a:hover {
        text-decoration: underline;
    }

    .tr-alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 25px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .pass-criteria {
        list-style: none;
        padding: 0;
        margin: 8px 0 0 5px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .pass-criteria li {
        font-size: 12px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.3s;
    }
    .pass-criteria li.valid { color: #16a34a; }
    
    @media (max-width: 576px) {
        .tr-login-wrapper {
            padding: 20px 15px;
        }
        .tr-login-container {
            padding: 30px 20px;
        }
        .tr-form-row {
            flex-direction: column;
            gap: 0;
        }
    }

    #regMap { 
        width: 100% !important;
        height: 180px !important; 
        min-height: 180px !important;
        border-radius: 6px; 
        border: 1px solid #e0e0e0; 
        position: relative; 
        z-index: 10; 
        display: block !important;
        transition: height 0.3s ease;
    }
    #regMap.map-big { 
        height: 400px !important; 
        min-height: 400px !important;
    } 

    @media (max-width: 992px) {
        .login-card {
            flex-direction: column;
            width: 100%;
        }
        .login-left {
            display: none;
        }
        .login-right {
            padding: 30px;
        }
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="tr-login-wrapper">
    <div class="tr-login-container">
        <div class="tr-logo-area">
            <i class="bi bi-building text-primary"></i>
        </div>
        
        <h1 class="tr-title">Real Estate Sign Up</h1>
        <p class="tr-subtitle">List your properties and get direct client inquiries.</p>

        @if ($errors->any())
            <div class="tr-alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i> 
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('property_owner.register') }}">
            @csrf
            
            <div class="tr-form-row">
                <div class="tr-form-col">
                    <div class="tr-form-group">
                        <label class="tr-form-label" for="ownerInput">Your Full Name</label>
                        <div class="tr-input-wrapper">
                            <input type="text" name="full_name" id="ownerInput" class="tr-form-control" placeholder="Owner" value="{{ old('full_name') }}" required>
                            <i class="bi bi-person tr-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="tr-form-col">
                    <div class="tr-form-group">
                        <label class="tr-form-label" for="shopInput">Agency / Company Name</label>
                        <div class="tr-input-wrapper">
                            <input type="text" name="agency_name" id="shopInput" class="tr-form-control" placeholder="Agency Name" value="{{ old('agency_name') }}" required>
                            <i class="bi bi-building tr-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tr-form-row">
                <div class="tr-form-col">
                    <div class="tr-form-group">
                        <label class="tr-form-label" for="emailInput">Business Email</label>
                        <div class="tr-input-wrapper">
                            <input type="email" name="email" id="emailInput" class="tr-form-control" placeholder="Email" value="{{ old('email') }}" required>
                            <i class="bi bi-envelope tr-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="tr-form-col">
                    <div class="tr-form-group">
                        <label class="tr-form-label" for="phoneInput">Phone (WhatsApp)</label>
                        <div class="tr-input-wrapper">
                            <input type="tel" name="phone" id="phoneInput" class="tr-form-control" placeholder="Phone" value="{{ old('phone') }}" required>
                            <i class="bi bi-telephone tr-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tr-form-group">
                <label class="tr-form-label" for="addrInput">Office Address (City, Street)</label>
                <div class="tr-input-wrapper">
                    <input type="text" name="address" id="addrInput" class="tr-form-control" placeholder="Address" value="{{ old('address') }}" required>
                    <i class="bi bi-geo-alt tr-icon"></i>
                </div>
            </div>

            <div class="tr-form-row">
                <div class="tr-form-col">
                    <div class="tr-form-group">
                        <label class="tr-form-label" for="passInput">Password</label>
                        <div class="tr-input-wrapper">
                            <input type="password" name="password" id="passInput" class="tr-form-control" placeholder="Create Strong Password" required>
                            <i class="bi bi-lock tr-icon"></i>
                        </div>
                        <ul class="pass-criteria">
                            <li id="crit-upper"><i class="bi bi-circle"></i> Upper (A-Z)</li>
                            <li id="crit-num"><i class="bi bi-circle"></i> Num (0-9)</li>
                            <li id="crit-special"><i class="bi bi-circle"></i> Special</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex flex-column gap-2">
                        <button type="button" onclick="getLocation()" class="tr-btn-submit" style="background-color: #f1f5f9; color: #4f46e5; margin-top: 0; box-shadow: none; border: 1px solid #e2e8f0;"><i class="bi bi-cursor-fill me-1"></i> Locate Me</button>
                        <button type="button" id="toggleMapSize" class="tr-btn-submit" style="background-color: #f1f5f9; color: #333; margin-top: 0; box-shadow: none; border: 1px solid #e2e8f0;"><i class="bi bi-arrows-fullscreen me-1"></i> View Big Map</button>
                    </div>
                </div>
                <div class="tr-form-col">
                    <label class="tr-form-label">Pin Office Location</label>
                    <div id="regMap" style="border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; height: 100%; min-height: 200px;"></div>
                    <input type="hidden" name="latitude" id="regLat" value="{{ old('latitude', '-1.9441') }}">
                    <input type="hidden" name="longitude" id="regLng" value="{{ old('longitude', '30.0619') }}">
                </div>
            </div>

            <button type="submit" class="tr-btn-submit mt-4">
                CREATE REAL ESTATE ACCOUNT <i class="bi bi-buildings ms-1"></i>
            </button>
            
            <div class="tr-register-link">
                Already have an account? <a href="{{ route('login') }}">Login Instead</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
let map, marker;
const defaultLat = parseFloat(document.getElementById('regLat').value) || -1.9441;
const defaultLng = parseFloat(document.getElementById('regLng').value) || 30.0619;

function initMap() {
    try {
        if (typeof L === 'undefined') {
            throw new Error('Leaflet library not loaded');
        }

        map = L.map('regMap').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        updateCoords(defaultLat, defaultLng);

        map.on('click', function(e) { 
            marker.setLatLng(e.latlng); 
            updateCoords(e.latlng.lat, e.latlng.lng); 
        });
        
        marker.on('dragend', function() { 
            const position = marker.getLatLng();
            updateCoords(position.lat, position.lng); 
        });

        if (typeof L.Control.geocoder !== 'undefined') {
            L.Control.geocoder({ defaultMarkGeocode: false, placeholder: "Search..." })
            .on('markgeocode', function(e) {
                const center = e.geocode.center;
                marker.setLatLng(center);
                map.setView(center, 16);
                updateCoords(center.lat, center.lng);
            }).addTo(map);
        }

        setTimeout(() => { map.invalidateSize(); }, 300);

    } catch (error) {
        console.error('Map execution engine halted:', error);
        const mapEl = document.getElementById('regMap');
        if (mapEl) {
            mapEl.innerHTML = '<div class="text-center p-4"><i class="bi bi-exclamation-triangle-fill text-warning"></i><p class="small mt-1">Map configuration error.</p></div>';
        }
    }
}

function updateCoords(lat, lng) {
    document.getElementById('regLat').value = Number(lat).toFixed(6);
    document.getElementById('regLng').value = Number(lng).toFixed(6);
}

document.addEventListener('DOMContentLoaded', function() {
    initMap();

    const passInput = document.getElementById('passInput');
    if(passInput) {
        passInput.addEventListener('input', function() {
            const val = this.value;
            updateCriterion('crit-upper', /[A-Z]/.test(val));
            updateCriterion('crit-num', /[0-9]/.test(val));
            updateCriterion('crit-special', /[^\w]/.test(val));
        });
    }

    function updateCriterion(id, isValid) {
        const el = document.getElementById(id);
        if (!el) return;
        const icon = el.querySelector('i');
        if (isValid) {
            el.classList.add('valid');
            if (icon) icon.className = 'bi bi-check-circle-fill';
        } else {
            el.classList.remove('valid');
            if (icon) icon.className = 'bi bi-circle';
        }
    }

    window.getLocation = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                const lat = pos.coords.latitude, lng = pos.coords.longitude;
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 16);
                updateCoords(lat, lng);
            });
        }
    };

    const toggleBtn = document.getElementById('toggleMapSize');
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const mapEl = document.getElementById('regMap');
            mapEl.classList.toggle('map-big');
            
            let frame = 0;
            const sizeAnimator = setInterval(() => {
                map.invalidateSize();
                frame++;
                if (frame > 15) clearInterval(sizeAnimator);
            }, 30);

            if (mapEl.classList.contains('map-big')) {
                this.innerHTML = '<i class="bi bi-fullscreen-exit me-1"></i> View Small Map';
            } else {
                this.innerHTML = '<i class="bi bi-arrows-fullscreen me-1"></i> View Big Map';
            }
        });
    }
});
</script>
@endsection
