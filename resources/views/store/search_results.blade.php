@extends('layouts.app')

@section('title', 'Search Results | Trust Rwanda')

@section('content')
<div class="catalog-banner text-white">
    <div class="container py-5">
        <h1 class="fw-extrabold m-0">Search Results</h1>
        @if(!empty($query))
            <p class="lead mt-2">Showing results for <strong>"{{ $query }}"</strong></p>
        @else
            <p class="lead mt-2">Showing latest items</p>
        @endif
    </div>
</div>

<div class="container py-5 mb-5">

    <!-- PROPERTIES SECTION -->
    @if($properties->count() > 0)
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0"><i class="bi bi-buildings text-primary me-2"></i> Real Estate Properties</h3>
            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $properties->count() }} Results</span>
        </div>
        
        <div class="row g-4 mb-5">
            @foreach($properties as $property)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 16px; transition: transform 0.3s ease;">
                        <div class="position-relative">
                            @php
                                $img = $property->images ? $property->images->first() : null;
                                $imgUrl = $img ? $img->image_url : 'https://placehold.co/600x400/eeeeee/999999?text=No+Image';
                            @endphp
                            <img src="{{ $imgUrl }}" class="card-img-top" alt="{{ $property->title }}" style="height: 200px; object-fit: cover;">
                            <span class="badge bg-{{ $property->listing_type == 'sale' ? 'danger' : 'success' }} position-absolute top-0 start-0 m-3 rounded-pill px-3 py-2 shadow-sm text-uppercase fw-bold" style="font-size: 0.75rem;">
                                For {{ $property->listing_type }}
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold text-truncate" title="{{ $property->title }}">{{ $property->title }}</h5>
                            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $property->address ?? $property->district }}</p>
                            
                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <span class="fw-extrabold text-primary fs-5">{{ number_format($property->price) }} RWF</span>
                                <a href="{{ route('properties.show', $property->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- PRODUCTS SECTION -->
    @if($products->count() > 0)
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0"><i class="bi bi-box-seam text-warning me-2"></i> Products & Marketplace</h3>
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">{{ $products->count() }} Results</span>
        </div>

        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 16px; transition: transform 0.3s ease;">
                        <div class="position-relative">
                            <img src="{{ $product->image_url ?? 'https://placehold.co/600x600/eeeeee/999999?text=No+Image' }}" class="card-img-top" alt="{{ $product->title }}" style="height: 200px; object-fit: cover;">
                            @if($product->category)
                                <span class="badge bg-dark position-absolute top-0 end-0 m-3 rounded-pill px-3 py-2 shadow-sm text-uppercase fw-bold" style="font-size: 0.75rem;">
                                    {{ str_replace('-', ' ', $product->category) }}
                                </span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold text-truncate" title="{{ $product->title }}">{{ $product->title }}</h5>
                            <p class="text-muted small mb-3 text-truncate">{{ $product->description }}</p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="fw-extrabold text-dark fs-5">{{ number_format($product->price) }} <small class="text-muted fs-6">{{ $product->price_unit ?? 'RWF' }}</small></span>
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary rounded-pill fw-bold shadow-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- NO RESULTS -->
    @if($products->count() == 0 && $properties->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
            <h3 class="fw-bold mt-3">No results found</h3>
            <p class="text-muted">We couldn't find anything matching your search. Try different keywords.</p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 mt-3 fw-bold">Back to Home</a>
        </div>
    @endif
</div>
@endsection
