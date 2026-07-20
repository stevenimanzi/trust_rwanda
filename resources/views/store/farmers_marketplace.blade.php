@extends('layouts.app')

@section('title', 'Farmers Marketplace | Trust Rwanda')

@section('content')
<!-- Hero Section -->
<div class="farm-hero">
    <div class="container text-center text-white py-5">
        <span class="badge bg-white text-success fw-bold px-3 py-2 mb-3 text-uppercase" style="letter-spacing:1px;"><i class="bi bi-flower1"></i> Farmers Direct</span>
        <h1 class="fw-extrabold mb-3">Fresh Harvest Marketplace</h1>
        <p class="fs-5 opacity-90 max-width-600 mx-auto mb-4">Directly buy organic fruits, fresh vegetables, grains, and dairy from certified Rwandan farmers with fast local dispatch.</p>
        
        <form action="{{ route('farmers.market') }}" method="GET" class="search-container">
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="text" name="q" class="search-input" placeholder="Search fresh produce..." value="{{ $search }}">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
</div>

<div class="container mb-5">
    <!-- Category pills -->
    <div class="d-flex gap-2 overflow-auto pb-4 justify-content-lg-center" style="scrollbar-width: none;">
        <a href="{{ route('farmers.market', ['category' => 'all']) }}" class="cat-pill text-decoration-none {{ $category === 'all' ? 'active' : '' }}">
            All Harvest
        </a>
        <a href="{{ route('farmers.market', ['category' => 'fruits']) }}" class="cat-pill text-decoration-none {{ $category === 'fruits' ? 'active' : '' }}">
            Fresh Fruits
        </a>
        <a href="{{ route('farmers.market', ['category' => 'vegetables']) }}" class="cat-pill text-decoration-none {{ $category === 'vegetables' ? 'active' : '' }}">
            Vegetables
        </a>
        <a href="{{ route('farmers.market', ['category' => 'grains']) }}" class="cat-pill text-decoration-none {{ $category === 'grains' ? 'active' : '' }}">
            Grains & Cereals
        </a>
        <a href="{{ route('farmers.market', ['category' => 'dairy']) }}" class="cat-pill text-decoration-none {{ $category === 'dairy' ? 'active' : '' }}">
            Dairy & Honey
        </a>
        <a href="{{ route('farmers.market', ['category' => 'meat']) }}" class="cat-pill text-decoration-none {{ $category === 'meat' ? 'active' : '' }}">
            Meat & Poultry
        </a>
    </div>

    <!-- Product Grid -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="text-muted fw-bold">{{ $products->total() }} organic items listed</span>
        <form action="{{ request()->url() }}" method="GET" class="d-flex align-items-center gap-2">
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="hidden" name="q" value="{{ $search }}">
            <select name="sort" class="form-select form-select-sm rounded-pill fw-bold border-secondary-subtle px-3" style="width: 170px;" onchange="this.form.submit()">
                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Harvest</option>
                <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="card text-center py-5 border-0 shadow-sm rounded-4">
            <div class="my-4"><i class="bi bi-flower1 display-1 text-muted opacity-25"></i></div>
            <h3 class="fw-bold">No Produce Available</h3>
            <p class="text-muted">No organic items match your filter criteria. Check back later for fresh listings.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border rounded-4 shadow-sm p-3 h-100 d-flex flex-column" style="transition:0.3s;">
                        <div class="mc-img" onclick="window.location.href='{{ route('products.show', $product->id) }}'" style="cursor:pointer;">
                            <img src="{{ kura_product_image_url($product->image_url, 'https://placehold.co/200') }}" class="img-fluid rounded" alt="{{ $product->title }}">
                        </div>
                        <div class="mt-2 flex-grow-1">
                            <span class="badge bg-success-subtle text-success small mb-1">{{ $product->category }}</span>
                            <h6 class="fw-bold text-dark mb-1" onclick="window.location.href='{{ route('products.show', $product->id) }}'" style="cursor:pointer;">{{ $product->title }}</h6>
                            <p class="small text-muted mb-2">Farm: {{ $product->vendor->shop_name ?? 'Local Farm' }}</p>
                            @if($product->harvest_date)
                                <div class="small text-secondary mb-2"><i class="bi bi-calendar-check"></i> Harvested: {{ $product->harvest_date }}</div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div>
                                <span class="fw-extrabold text-danger fs-5">{{ number_format($product->price) }} RWF</span>
                                <small class="text-muted d-block">/ {{ $product->price_unit ?? 'kg' }}</small>
                            </div>
                            <button type="button" onclick="addToCart({{ $product->id }}, this)" class="mc-add-cart bg-success text-white border-0" style="width:36px; height:36px; border-radius:50%;"><i class="bi bi-cart-plus"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .farm-hero {
        background: linear-gradient(135deg, rgba(4, 120, 87, 0.9) 0%, rgba(16, 185, 129, 0.85) 100%);
        border-radius: 0 0 40px 40px;
    }
    .search-container {
        position: relative;
        max-width: 650px;
        margin: 0 auto;
        background: white;
        padding: 6px;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        display: flex;
    }
    .search-input {
        flex: 1; border: none; padding: 0.8rem 1.5rem; font-size: 1.05rem;
        border-radius: 50px 0 0 50px; outline: none;
    }
    .search-btn { background: #10b981; border: none; color: white; padding: 0 2rem; border-radius: 50px; font-weight: 700; transition: 0.3s; }
    .search-btn:hover { background: #047857; }
    .cat-pill {
        background: white; border: 1px solid #e2e8f0;
        padding: 0.7rem 1.6rem; border-radius: 50rem;
        color: #475569; font-weight: 700; text-decoration: none;
        transition: all 0.3s;
        white-space: nowrap;
    }
    .cat-pill:hover, .cat-pill.active {
        border-color: #10b981; color: white; background: #10b981;
    }
</style>
@endsection
