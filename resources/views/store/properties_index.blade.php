@extends('layouts.app')
@section('title', 'Real Estate & Properties - Trust Rwanda')
@section('content')

<style>
    body { background-color: #f8fafc; }
    
    .prop-banner {
        background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
        padding: 60px 0;
        margin-bottom: 40px;
        color: white;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    .prop-banner::after {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: url('https://placehold.co/1200x400/0f172a/1e293b?text=Pattern') center/cover;
        opacity: 0.1;
        pointer-events: none;
    }
    .prop-banner h1 { font-weight: 900; font-size: 2.5rem; margin-bottom: 10px; }
    .prop-banner p { font-size: 1.1rem; opacity: 0.8; margin-bottom: 0; }

    /* Property Card (Same style as homepage but adjusted for grid) */
    .prop-card-custom {
        background: white; border: 1px solid #e2e8f0; border-radius: var(--radius-lg);
        overflow: hidden; transition: all 0.3s; position: relative; height: 100%; display: flex; flex-direction: column;
    }
    .prop-card-custom:hover { box-shadow: 0 15px 35px rgba(0,0,0,0.06); transform: translateY(-5px); }
    .prop-img-box { position: relative; height: 220px; cursor: pointer; overflow: hidden; }
    .prop-img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .prop-card-custom:hover .prop-img-box img { transform: scale(1.05); }
    
    .prop-badge-status {
        position: absolute; top: 12px; left: 12px; z-index: 2; padding: 4px 12px; border-radius: 50px;
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .prop-badge-status.rent { background: var(--mega-blue); color: white; }
    .prop-badge-status.sale { background: var(--mega-red); color: white; }
    
    .prop-info-body { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
    .prop-loc-row { font-size: 0.8rem; color: #64748b; margin-bottom: 6px; font-weight: 600; }
    .prop-title { font-size: 1.15rem; font-weight: 800; color: var(--mega-dark); text-decoration: none; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3; }
    .prop-title:hover { color: var(--primary); }
    
    .prop-specs { display: flex; gap: 15px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9; }
    .prop-specs span { font-size: 0.85rem; color: #475569; font-weight: 700; display: flex; align-items: center; gap: 5px; }
    .prop-specs i { color: #94a3b8; font-size: 1.1rem; }
    
    .prop-price-footer { margin-top: auto; display: flex; align-items: flex-end; justify-content: space-between; gap: 10px; }
    .prop-price-val { font-size: 1.3rem; font-weight: 900; color: var(--primary); display: block; line-height: 1; margin-bottom: 2px; }
    .prop-price-subtext { font-size: 0.75rem; color: #94a3b8; font-weight: 600; }
    .prop-actions-row { display: flex; gap: 8px; }
    .btn-prop-primary { background: #f8fafc; color: var(--primary); font-weight: 800; font-size: 0.8rem; padding: 8px 16px; border-radius: var(--radius-sm); border: 1px solid #e2e8f0; text-decoration: none; transition: 0.2s; }
    .btn-prop-primary:hover { background: var(--primary); color: white; border-color: var(--primary); }
    .btn-prop-wa { background: #10b981; color: white; border: none; padding: 8px 12px; border-radius: var(--radius-sm); transition: 0.2s; display: flex; align-items: center; justify-content: center; text-decoration: none; }
    .btn-prop-wa:hover { background: #059669; color: white; }

</style>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Real Estate</li>
        </ol>
    </nav>

    <!-- Banner -->
    <div class="prop-banner px-4 px-md-5 text-center text-md-start">
        <div class="position-relative" style="z-index: 2;">
            <h1>Discover Premium Properties</h1>
            <p>Find your perfect home, apartment, or land in Rwanda's top locations.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px; z-index: 10;">
                <div class="card-body p-4">
                    <form action="{{ route('properties.index') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control rounded-start-pill border-end-0 border-secondary-subtle" placeholder="Search locations..." value="{{ request('q') }}">
                            @if(request()->has('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request()->has('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <button class="btn btn-primary rounded-end-pill px-3" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <h5 class="fw-bold mb-4">Property Filters</h5>
                    <form action="{{ route('properties.index') }}" method="GET">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Listing Category</label>
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('properties.index', ['category' => 'all', 'q' => request('q'), 'sort' => request('sort')]) }}" class="text-decoration-none {{ $category === 'all' ? 'text-primary fw-bold' : 'text-muted' }}">All Properties</a>
                                <a href="{{ route('properties.index', ['category' => 'rent-house', 'q' => request('q'), 'sort' => request('sort')]) }}" class="text-decoration-none {{ $category === 'rent-house' ? 'text-primary fw-bold' : 'text-muted' }}">Houses for Rent</a>
                                <a href="{{ route('properties.index', ['category' => 'rent-apartment', 'q' => request('q'), 'sort' => request('sort')]) }}" class="text-decoration-none {{ $category === 'rent-apartment' ? 'text-primary fw-bold' : 'text-muted' }}">Apartments for Rent</a>
                                <a href="{{ route('properties.index', ['category' => 'sale-house', 'q' => request('q'), 'sort' => request('sort')]) }}" class="text-decoration-none {{ $category === 'sale-house' ? 'text-primary fw-bold' : 'text-muted' }}">Houses for Sale</a>
                                <a href="{{ route('properties.index', ['category' => 'sale-land', 'q' => request('q'), 'sort' => request('sort')]) }}" class="text-decoration-none {{ $category === 'sale-land' ? 'text-primary fw-bold' : 'text-muted' }}">Land for Sale</a>
                                <a href="{{ route('properties.index', ['category' => 'commercial', 'q' => request('q'), 'sort' => request('sort')]) }}" class="text-decoration-none {{ $category === 'commercial' ? 'text-primary fw-bold' : 'text-muted' }}">Commercial</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Listings -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="text-muted fw-bold">{{ $properties->total() }} properties found</span>
                <form action="{{ request()->url() }}" method="GET" class="d-flex align-items-center gap-2">
                    @if(request()->has('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request()->has('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <select name="sort" class="form-select form-select-sm rounded-pill fw-bold border-secondary-subtle px-3" style="width: 170px;" onchange="this.form.submit()">
                        <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest Listings</option>
                        <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            @if($properties->isEmpty())
                <div class="card text-center py-5 border-0 shadow-sm rounded-4">
                    <div class="my-4"><i class="bi bi-houses display-1 text-muted opacity-25"></i></div>
                    <h3 class="fw-bold">No Properties Found</h3>
                    <p class="text-muted">We couldn't find any properties matching your selected category.</p>
                    <a href="{{ route('properties.index') }}" class="btn btn-primary rounded-pill px-4 align-self-center fw-bold">View All Properties</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($properties as $prop)
                        @php
                            $isRent = ($prop->listing_type === 'rent');
                            $badgeText = $isRent ? 'For Rent' : 'For Sale';
                            $badgeClass = $isRent ? 'rent' : 'sale';
                            $beds = $prop->features->where('feature_name', 'Bedrooms')->first()->feature_value ?? '-'; 
                            $baths = $prop->features->where('feature_name', 'Bathrooms')->first()->feature_value ?? '-';
                            $imageUrl = $prop->images->first()->image_url ?? null;
                            $imageUrl = $imageUrl ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset($imageUrl)) : 'https://placehold.co/400x250?text=Property';
                        @endphp
                        <div class="col-md-6 col-lg-6">
                            <div class="prop-card-custom">
                                <span class="prop-badge-status {{$badgeClass}}">{{$badgeText}}</span>
                                <div class="prop-img-box" onclick="window.location.href='{{ route('properties.show', $prop->id) }}'">
                                    <img src="{{ $imageUrl }}" onerror="this.src='https://placehold.co/400x250?text=Property';">
                                </div>
                                <div class="prop-info-body">
                                    <div class="prop-loc-row">
                                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ $prop->district }}, {{ $prop->sector }}
                                    </div>
                                    <a href="{{ route('properties.show', $prop->id) }}" class="prop-title">{{$prop->title}}</a>
                                    
                                    <div class="prop-specs">
                                        <span><i class="bi bi-door-open"></i> {{$beds}} Beds</span>
                                        <span><i class="bi bi-droplet"></i> {{$baths}} Baths</span>
                                    </div>

                                    <div class="prop-price-footer">
                                        <div>
                                            <span class="prop-price-val">{{number_format($prop->price)}} Rwf</span>
                                            <span class="prop-price-subtext">{{$isRent ? '/ month' : ''}}</span>
                                        </div>
                                        <div class="prop-actions-row">
                                            <a href="{{ route('properties.show', $prop->id) }}" class="btn-prop-primary">Details</a>
                                            <a href="https://wa.me/{{preg_replace('/[^0-9]/', '', $prop->owner->phone ?? '250796194401')}}?text=Hello,%20I%20am%20interested%20in%20your%20property%20listing:%20{{urlencode($prop->title)}}" target="_blank" class="btn-prop-wa">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $properties->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
