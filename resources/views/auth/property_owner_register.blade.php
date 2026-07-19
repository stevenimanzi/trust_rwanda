@extends('layouts.app')

@section('title', 'Property Owner Registration - Trust Rwanda')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    body {
        background-color: #dfdbed;
        margin: 0;
        padding-top: 100px;
    }
    
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 120px);
        padding: 20px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .login-card {
        display: flex;
        width: 1100px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .login-left {
        flex: 1;
        background: linear-gradient(180deg, #f2f7ff 0%, #e1fbef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        position: relative;
    }

    .login-right {
        flex: 1.5;
        padding: 40px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-heading {
        color: #1a428a;
        font-weight: 800;
        font-size: 24px;
        margin-bottom: 5px;
        letter-spacing: 1px;
    }

    .login-subheading {
        color: #b0b0b0;
        font-size: 14px;
        margin-bottom: 25px;
        line-height: 1.5;
    }

    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-col {
        flex: 1;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        color: #9e9e9e;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-field {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 13px;
        color: #333;
        outline: none;
        transition: border-color 0.3s;
        box-sizing: border-box;
    }

    .input-field::placeholder {
        color: #d0d0d0;
    }

    .input-field:focus {
        border-color: #1a428a;
    }

    .input-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #b0b0b0;
        font-size: 16px;
    }

    .login-btn {
        width: 100%;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        padding: 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 15px;
    }

    .login-btn:hover {
        background-color: #1d4ed8;
    }
    
    .pass-criteria {
        list-style: none;
        padding: 0;
        margin: 5px 0 0 5px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .pass-criteria li {
        font-size: 11px;
        color: #b0b0b0;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: color 0.3s;
    }
    .pass-criteria li.valid { color: #2563eb; }

    .tr-alert {
        padding: 12px 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tr-alert-danger {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
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
<div class="login-container">
    <div class="login-card">
        <div class="login-left">
            <svg viewBox="0 0 400 300" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
                <!-- Monitor Base -->
                <rect x="80" y="80" width="240" height="150" rx="15" fill="#1a428a" />
                <rect x="90" y="90" width="220" height="130" fill="#ffffff" />
                <rect x="160" y="230" width="80" height="10" fill="#1a428a" />
                
                <!-- Registration form inside monitor -->
                <rect x="120" y="105" width="160" height="100" rx="5" fill="#f8f9fa" stroke="#e0e0e0" stroke-width="2"/>
                <!-- Building Icon -->
                <rect x="180" y="115" width="40" height="30" fill="#c4d2e8" />
                <rect x="185" y="120" width="8" height="8" fill="#1a428a" />
                <rect x="205" y="120" width="8" height="8" fill="#1a428a" />
                <rect x="185" y="135" width="8" height="8" fill="#1a428a" />
                <rect x="205" y="135" width="8" height="8" fill="#1a428a" />
                
                <rect x="135" y="155" width="130" height="6" rx="3" fill="#1a428a" opacity="0.3"/>
                <rect x="135" y="170" width="130" height="6" rx="3" fill="#1a428a" opacity="0.3"/>
                <rect x="135" y="185" width="130" height="6" rx="3" fill="#1a428a" opacity="0.3"/>
                
                <!-- Shield overlay -->
                <path d="M50 160 L100 160 L100 210 Q75 240 50 210 Z" fill="#ffb74d" />
                <path d="M55 165 L95 165 L95 205 Q75 230 55 205 Z" fill="#1a428a" />
                <!-- Plus in shield -->
                <rect x="71" y="175" width="8" height="20" rx="2" fill="#ffffff" />
                <rect x="65" y="181" width="20" height="8" rx="2" fill="#ffffff" />
                
                <!-- Plant (Now Blue) -->
                <path d="M320 220 Q310 180 320 160 Q330 180 320 220" fill="#3b82f6" />
                <path d="M320 220 Q335 190 340 170 Q325 190 320 220" fill="#2563eb" />
                <path d="M320 220 Q305 190 300 170 Q315 190 320 220" fill="#60a5fa" />
                <rect x="305" y="220" width="30" height="20" rx="3" fill="#8c7ae6" />
            </svg>
        </div>
        
        <div class="login-right">
            <h1 class="login-heading">REAL ESTATE SIGN UP</h1>
            <p class="login-subheading">List your properties and get direct client inquiries.</p>

            @if ($errors->any())
                <div class="tr-alert tr-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('property_owner.register') }}">
                @csrf
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="ownerInput">Your Full Name</label>
                            <div class="input-wrapper">
                                <input type="text" name="full_name" id="ownerInput" class="input-field" placeholder="Owner" value="{{ old('full_name') }}" required>
                                <i class="bi bi-person input-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="shopInput">Agency / Company Name</label>
                            <div class="input-wrapper">
                                <input type="text" name="agency_name" id="shopInput" class="input-field" placeholder="Agency Name" value="{{ old('agency_name') }}" required>
                                <i class="bi bi-building input-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="emailInput">Business Email</label>
                            <div class="input-wrapper">
                                <input type="email" name="email" id="emailInput" class="input-field" placeholder="Email" value="{{ old('email') }}" required>
                                <i class="bi bi-envelope input-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="phoneInput">Phone (WhatsApp)</label>
                            <div class="input-wrapper">
                                <input type="tel" name="phone" id="phoneInput" class="input-field" placeholder="Phone" value="{{ old('phone') }}" required>
                                <i class="bi bi-telephone input-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="addrInput">Office Address (City, Street)</label>
                    <div class="input-wrapper">
                        <input type="text" name="address" id="addrInput" class="input-field" placeholder="Address" value="{{ old('address') }}" required>
                        <i class="bi bi-geo-alt input-icon"></i>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="passInput">Password</label>
                            <div class="input-wrapper">
                                <input type="password" name="password" id="passInput" class="input-field" placeholder="Create Strong Password" required>
                                <i class="bi bi-lock input-icon"></i>
                            </div>
                            <ul class="pass-criteria">
                                <li id="crit-upper"><i class="bi bi-circle"></i> Upper (A-Z)</li>
                                <li id="crit-num"><i class="bi bi-circle"></i> Num (0-9)</li>
                                <li id="crit-special"><i class="bi bi-circle"></i> Special</li>
                            </ul>
                        </div>
                        
                        <div>
                            <button type="button" onclick="getLocation()" class="login-btn" style="background-color: #f1f5f9; color: #1a428a; padding: 10px; margin-top: 5px;"><i class="bi bi-cursor-fill me-1"></i> Locate Me</button>
                            <button type="button" id="toggleMapSize" class="login-btn" style="background-color: #f1f5f9; color: #333; padding: 10px; margin-top: 5px;"><i class="bi bi-arrows-fullscreen me-1"></i> View Big Map</button>
                        </div>
                    </div>
                    <div class="form-col">
                        <label class="form-label">Pin Office Location</label>
                        <div id="regMap"></div>
                        <input type="hidden" name="latitude" id="regLat" value="{{ old('latitude', '-1.9441') }}">
                        <input type="hidden" name="longitude" id="regLng" value="{{ old('longitude', '30.0619') }}">
                    </div>
                </div>

                <button type="submit" class="login-btn">CREATE REAL ESTATE ACCOUNT</button>
                
                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ route('login') }}" style="color: #1a428a; font-size: 12px; font-weight: 700; text-decoration: none;">Already have an account? Login Instead</a>
                </div>
            </form>
        </div>
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
