@extends('layouts.app')
@section('title', $property->title . ' - Trust Rwanda')
@section('content')

<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    body { background-color: #f8fafc; }
    
    /* ════════ HERO GALLERY ════════ */
    .prop-hero-gallery {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        grid-template-rows: 250px 250px;
        gap: 15px;
        margin-bottom: 40px;
        border-radius: 20px;
        overflow: hidden;
    }
    .prop-gallery-item {
        position: relative;
        overflow: hidden;
        background: #e2e8f0;
    }
    .prop-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
        cursor: pointer;
    }
    .prop-gallery-item:hover img {
        transform: scale(1.05);
    }
    .prop-gallery-item.main-img {
        grid-row: 1 / 3;
    }
    .view-all-photos {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(255,255,255,0.9);
        color: #0f172a;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.9rem;
        cursor: pointer;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: 0.2s;
    }
    .view-all-photos:hover { background: white; transform: translateY(-2px); }

    /* ════════ PROPERTY HEADER ════════ */
    .prop-type-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #e0f2fe;
        color: #0284c7;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }
    .prop-title {
        font-size: 2.2rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 10px;
    }
    .prop-address {
        font-size: 1.1rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 25px;
    }

    /* ════════ QUICK SPECS ════════ */
    .quick-specs {
        display: flex;
        gap: 20px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .spec-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .spec-icon {
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #3b82f6;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    .spec-details span { display: block; }
    .spec-val { font-size: 1.1rem; font-weight: 800; color: #0f172a; line-height: 1.2; }
    .spec-lbl { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

    /* ════════ SECTIONS ════════ */
    .prop-section-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 20px;
    }
    .prop-desc {
        color: #475569;
        font-size: 1.05rem;
        line-height: 1.8;
        margin-bottom: 40px;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 40px;
    }
    .feature-pill {
        background: white;
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #334155;
    }
    .feature-pill i { color: #10b981; font-size: 1.2rem; }

    /* ════════ MAP ════════ */
    #propertyMap {
        height: 400px;
        border-radius: 20px;
        margin-bottom: 40px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.02);
        z-index: 1; /* Keep leaflet controls below sticky headers */
    }

    /* ════════ STICKY CONTACT CARD ════════ */
    .sticky-contact-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.03);
        position: sticky;
        top: 100px;
    }
    .price-tag {
        font-size: 2.2rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 5px;
    }
    .price-period {
        color: #64748b;
        font-size: 1rem;
        font-weight: 600;
    }
    .agent-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px 0;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        margin: 25px 0;
    }
    .agent-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 800;
    }
    .agent-avatar img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
    .agent-info h5 { margin: 0; font-weight: 800; font-size: 1.1rem; color: #0f172a; }
    .agent-info p { margin: 0; font-size: 0.85rem; color: #64748b; font-weight: 600; }
    
    .btn-contact {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: 0.3s;
        margin-bottom: 12px;
        border: none;
    }
    .btn-whatsapp { background: #10b981; color: white; }
    .btn-whatsapp:hover { background: #059669; transform: translateY(-2px); }
    .btn-call { background: #f1f5f9; color: #0f172a; border: 1px solid #e2e8f0; }
    .btn-call:hover { background: #e2e8f0; }

    @media (max-width: 991px) {
        .prop-hero-gallery {
            grid-template-columns: 1fr;
            grid-template-rows: 300px;
        }
        .prop-gallery-item:not(.main-img) { display: none; }
    }
</style>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/real_estate') }}" class="text-decoration-none text-muted">Real Estate</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ Str::limit($property->title, 40) }}</li>
        </ol>
    </nav>

    <!-- Hero Gallery -->
    @php
        $images = $property->images;
        $mainImage = $images->first();
        $otherImages = $images->skip(1)->take(4);
    @endphp
    
    <div class="prop-hero-gallery">
        <div class="prop-gallery-item main-img">
            @php 
                $imgUrl = $mainImage->image_url ?? null;
                $imgUrl = $imgUrl ? (str_starts_with($imgUrl, 'http') ? $imgUrl : asset($imgUrl)) : 'https://placehold.co/1200x800?text=Property';
            @endphp
            <img src="{{ $imgUrl }}" alt="Property Main Image" data-bs-toggle="modal" data-bs-target="#galleryModal">
            <button class="view-all-photos" data-bs-toggle="modal" data-bs-target="#galleryModal">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i> View all photos
            </button>
        </div>
        
        @foreach($otherImages as $idx => $img)
            @php 
                $otherUrl = $img->image_url ? (str_starts_with($img->image_url, 'http') ? $img->image_url : asset($img->image_url)) : 'https://placehold.co/600x400?text=Property';
            @endphp
            <div class="prop-gallery-item">
                <img src="{{ $otherUrl }}" alt="Property Image {{$idx}}" data-bs-toggle="modal" data-bs-target="#galleryModal">
            </div>
        @endforeach
        
        <!-- Fill empty spots if less than 5 images -->
        @for($i = $otherImages->count(); $i < 4; $i++)
            <div class="prop-gallery-item">
                <img src="https://placehold.co/600x400?text=Photo+Coming+Soon" alt="Placeholder">
            </div>
        @endfor
    </div>

    <!-- Main Content Row -->
    <div class="row g-5">
        
        <!-- Left Column: Details -->
        <div class="col-lg-8">
            <span class="prop-type-badge">
                {{ ucfirst($property->listing_type) }} - {{ ucfirst($property->property_type) }}
            </span>
            <h1 class="prop-title">{{ $property->title }}</h1>
            <div class="prop-address">
                <i class="bi bi-geo-alt-fill text-danger fs-5"></i> 
                {{ $property->address }}, {{ $property->sector }}, {{ $property->district }} District, Kigali
            </div>

            <!-- Quick Specs -->
            <div class="quick-specs">
                @if($property->property_type !== 'land')
                    @php
                        $beds = $property->features->where('feature_name', 'Bedrooms')->first()->feature_value ?? '-';
                        $baths = $property->features->where('feature_name', 'Bathrooms')->first()->feature_value ?? '-';
                        $area = $property->features->where('feature_name', 'Area Size (sqm)')->first()->feature_value ?? '-';
                    @endphp
                    <div class="spec-item">
                        <div class="spec-icon"><i class="bi bi-door-open-fill"></i></div>
                        <div class="spec-details">
                            <span class="spec-val">{{ $beds }}</span>
                            <span class="spec-lbl">Bedrooms</span>
                        </div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-icon"><i class="bi bi-droplet-fill"></i></div>
                        <div class="spec-details">
                            <span class="spec-val">{{ $baths }}</span>
                            <span class="spec-lbl">Bathrooms</span>
                        </div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-icon"><i class="bi bi-aspect-ratio"></i></div>
                        <div class="spec-details">
                            <span class="spec-val">{{ $area }}</span>
                            <span class="spec-lbl">Sq. Meters</span>
                        </div>
                    </div>
                @else
                    <div class="spec-item">
                        <div class="spec-icon"><i class="bi bi-textarea"></i></div>
                        <div class="spec-details">
                            <span class="spec-val">{{ $property->features->where('feature_name', 'Area Size (sqm)')->first()->feature_value ?? 'Unknown' }}</span>
                            <span class="spec-lbl">Plot Size (Sqm)</span>
                        </div>
                    </div>
                @endif
                <div class="spec-item">
                    <div class="spec-icon"><i class="bi bi-check-circle-fill text-success"></i></div>
                    <div class="spec-details">
                        <span class="spec-val">Available</span>
                        <span class="spec-lbl">Status</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <h3 class="prop-section-title">About this property</h3>
            <div class="prop-desc">
                {!! nl2br(e($property->description)) !!}
            </div>

            <!-- Features (If any other features exist) -->
            @php
                $otherFeatures = $property->features->whereNotIn('feature_name', ['Bedrooms', 'Bathrooms', 'Area Size (sqm)']);
            @endphp
            @if($otherFeatures->isNotEmpty())
                <h3 class="prop-section-title">Amenities & Features</h3>
                <div class="features-grid">
                    @foreach($otherFeatures as $feat)
                        <div class="feature-pill">
                            <i class="bi bi-check2-circle"></i>
                            <div>
                                <span class="d-block small text-muted">{{ $feat->feature_name }}</span>
                                <span>{{ $feat->feature_value }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Location Map -->
            @if($property->latitude && $property->longitude)
                <h3 class="prop-section-title">Location</h3>
                <p class="text-muted"><i class="bi bi-geo-fill me-1"></i> Exact location pinned on the map.</p>
                <div id="propertyMap"></div>
            @endif

        </div>

        <!-- Right Column: Sticky Contact -->
        <div class="col-lg-4">
            <div class="sticky-contact-card">
                <div class="price-tag">{{ number_format($property->price) }} <span class="fs-5">Rwf</span></div>
                <div class="price-period">
                    @if($property->listing_type === 'rent')
                        / {{ $property->price_period ?? 'month' }}
                    @else
                        One-time payment
                    @endif
                </div>

                <div class="agent-card">
                    <div class="agent-avatar">
                        @if($property->owner && $property->owner->profile_photo)
                            <img src="{{ str_starts_with($property->owner->profile_photo, 'http') ? $property->owner->profile_photo : asset($property->owner->profile_photo) }}" alt="Agent">
                        @else
                            {{ strtoupper(substr($property->owner->full_name ?? 'A', 0, 1)) }}
                        @endif
                    </div>
                    <div class="agent-info">
                        <h5>{{ $property->owner->full_name ?? 'Verified Agent' }}</h5>
                        <p>Property Owner / Agent</p>
                    </div>
                </div>

                @php
                    $phone = preg_replace('/[^0-9]/', '', $property->owner->phone ?? '250796194401');
                    $waMsg = "Hello, I am interested in your property listing: " . $property->title;
                @endphp
                
                <a href="https://wa.me/{{ $phone }}?text={{ urlencode($waMsg) }}" target="_blank" class="btn-contact btn-whatsapp text-decoration-none">
                    <i class="bi bi-whatsapp fs-5"></i> Message on WhatsApp
                </a>
                <a href="tel:+{{ $phone }}" class="btn-contact btn-call text-decoration-none">
                    <i class="bi bi-telephone fs-5"></i> Call +{{ $phone }}
                </a>

                <p class="text-center text-muted small mt-4 mb-0">
                    <i class="bi bi-shield-check text-success"></i> 
                    Protected by Trust Rwanda safe dealing policy. Never send money before viewing.
                </p>
            </div>
        </div>

    </div>

    <!-- Related Properties -->
    @if($related->isNotEmpty())
        <div class="mt-5 pt-5 border-top">
            <h3 class="prop-section-title mb-4">Similar Properties</h3>
            <div class="row g-4">
                @foreach($related as $rel)
                    <div class="col-md-3">
                        <a href="{{ route('properties.show', $rel->id) }}" class="text-decoration-none">
                            <div class="card border-0 h-100 bg-white" style="border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s; overflow:hidden;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                <div style="height: 180px;">
                                    @php 
                                        $rImgUrl = $rel->images->first()->image_url ?? null;
                                        $rImgUrl = $rImgUrl ? (str_starts_with($rImgUrl, 'http') ? $rImgUrl : asset($rImgUrl)) : 'https://placehold.co/400x250?text=Property';
                                    @endphp
                                    <img src="{{ $rImgUrl }}" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge {{ $rel->listing_type === 'rent' ? 'bg-primary' : 'bg-danger' }}">{{ ucfirst($rel->listing_type) }}</span>
                                        <span class="fw-bold text-dark fs-6">{{ number_format($rel->price) }} Rwf</span>
                                    </div>
                                    <h6 class="text-dark fw-bold mb-1 text-truncate">{{ $rel->title }}</h6>
                                    <p class="text-muted small mb-0"><i class="bi bi-geo-alt-fill"></i> {{ $rel->district }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Gallery Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0 bg-transparent position-absolute w-100 z-3">
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center">
                <div id="propertyCarousel" class="carousel slide w-100 h-100" data-bs-ride="carousel">
                    <div class="carousel-inner h-100">
                        @foreach($images as $idx => $img)
                            @php 
                                $mImgUrl = $img->image_url ? (str_starts_with($img->image_url, 'http') ? $img->image_url : asset($img->image_url)) : 'https://placehold.co/1200x800?text=Property';
                            @endphp
                            <div class="carousel-item h-100 {{ $idx === 0 ? 'active' : '' }}">
                                <img src="{{ $mImgUrl }}" class="d-block w-100 h-100 object-fit-contain" alt="Property Image">
                            </div>
                        @endforeach
                    </div>
                    @if($images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@if($property->latitude && $property->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var lat = {{ $property->latitude }};
        var lng = {{ $property->longitude }};
        
        var map = L.map('propertyMap').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup("<b>{{ $property->title }}</b><br>Approximate Location").openPopup();
        
        // Ensure map renders correctly if it was in a hidden tab/container initially
        setTimeout(() => { map.invalidateSize(); }, 500);
    });
</script>
@endif
@endsection
