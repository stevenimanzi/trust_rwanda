@extends('layouts.app')

@section('title', $pageTitle . ' | Trust Rwanda')

@section('content')
<!-- Catalog Banner -->
<div class="catalog-banner">
    <div class="container py-4 text-white">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Marketplace</li>
            </ol>
        </nav>
        <h1 class="fw-extrabold m-0">{{ $pageTitle }}</h1>
    </div>
</div>

<div class="container mb-5">
    <!-- Electronics or Second-hand Subcategories Pills -->
    @if ($isElectronicsPage && !empty($electronicsSubCats))
        <div class="d-flex gap-2 overflow-auto pb-4" style="scrollbar-width: none;">
            <a href="{{ route('products.index', ['category' => 'electronics', 'sub' => 'all']) }}" class="elec-cat-pill text-decoration-none {{ $selectedSubCat === 'all' ? 'active' : '' }}">
                All Electronics
            </a>
            @foreach ($electronicsSubCats as $sub)
                <a href="{{ route('products.index', ['category' => 'electronics', 'sub' => $sub['slug']]) }}" class="elec-cat-pill text-decoration-none {{ $selectedSubCat === $sub['slug'] ? 'active' : '' }}">
                    {{ $sub['name'] }}
                </a>
            @endforeach
        </div>
    @elseif ($isSecondHandPage && !empty($secondHandSubCats))
        <div class="d-flex gap-2 overflow-auto pb-4" style="scrollbar-width: none;">
            <a href="{{ route('products.index', ['category' => 'second-hand', 'sub' => 'all']) }}" class="elec-cat-pill text-decoration-none {{ $selectedSubCat === 'all' ? 'active' : '' }}">
                All Used Goods
            </a>
            @foreach ($secondHandSubCats as $sub)
                <a href="{{ route('products.index', ['category' => 'second-hand', 'sub' => $sub['slug']]) }}" class="elec-cat-pill text-decoration-none {{ $selectedSubCat === $sub['slug'] ? 'active' : '' }}">
                    {{ $sub['name'] }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="card border shadow-sm p-3 sticky-top" style="top: 130px; border-radius: 16px;">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel"></i> Categories</h5>
                <div class="list-group list-group-flush">
                    <a href="{{ route('products.index', ['category' => 'all']) }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between border-0 px-2 rounded-3 fw-bold mb-1 {{ $category === 'all' ? 'bg-primary-subtle text-primary' : 'text-secondary' }}">
                        <span><i class="bi bi-grid me-2"></i> All Products</span>
                    </a>
                    <a href="{{ route('farmers.market') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between border-0 px-2 rounded-3 fw-bold mb-1 text-secondary">
                        <span><i class="bi bi-flower1 me-2 text-success"></i> Farmers Market</span>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'electronics']) }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between border-0 px-2 rounded-3 fw-bold mb-1 {{ $category === 'electronics' ? 'bg-primary-subtle text-primary' : 'text-secondary' }}">
                        <span><i class="bi bi-laptop me-2"></i> Electronics</span>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'second-hand']) }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between border-0 px-2 rounded-3 fw-bold mb-1 {{ $category === 'second-hand' ? 'bg-primary-subtle text-primary' : 'text-secondary' }}">
                        <span><i class="bi bi-recycle me-2"></i> Second Hand</span>
                    </a>
                    <a href="{{ route('real_estate') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between border-0 px-2 rounded-3 fw-bold mb-1 text-secondary">
                        <span><i class="bi bi-buildings me-2"></i> Real Estate</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Catalog Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="text-muted fw-bold">{{ $products->total() }} items found</span>
                <form action="{{ request()->url() }}" method="GET" class="d-flex align-items-center gap-2">
                    @if(request()->has('category'))
                        <input type="hidden" name="category" value="{{ request()->query('category') }}">
                    @endif
                    @if(request()->has('sub'))
                        <input type="hidden" name="sub" value="{{ request()->query('sub') }}">
                    @endif
                    @if(request()->has('q'))
                        <input type="hidden" name="q" value="{{ request()->query('q') }}">
                    @endif
                    <select name="sort" class="form-select form-select-sm rounded-pill fw-bold border-secondary-subtle px-3" style="width: 170px;" onchange="this.form.submit()">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            @if($products->isEmpty())
                <div class="card text-center py-5 border-0 shadow-sm rounded-4">
                    <div class="my-4"><i class="bi bi-search display-1 text-muted opacity-25"></i></div>
                    <h3 class="fw-bold">No Products Found</h3>
                    <p class="text-muted">We couldn't find any products matching your selection. Try adjusting your search query or category filters.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 align-self-center fw-bold">Clear Filters</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">
                            <div class="mega-card shadow-sm border rounded-4 d-flex flex-column h-100 p-3">
                                <div class="mc-img position-relative" onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                                    <img src="{{ kura_product_image_url($product->image_url) }}" class="img-fluid rounded w-100" style="height: 180px; object-fit: cover;" alt="{{ $product->title }}">
                                </div>
                                <span class="mc-cat text-muted mt-3">{{ $product->category }}</span>
                                <a href="{{ route('products.show', $product->id) }}" class="mc-title text-decoration-none text-dark fw-bold mb-1">{{ $product->title }}</a>
                                <div class="small text-secondary mb-2"><i class="bi bi-shop text-primary"></i> {{ $product->vendor ? $product->vendor->shop_name : 'Independent Seller' }}</div>
                                
                                <div class="mc-footer mt-auto d-flex justify-content-between align-items-center">
                                    <span class="mc-price fw-bold text-danger">{{ number_format($product->price) }} RWF</span>
                                    <button type="button" onclick="addToCart({{ $product->id }}, this)" class="btn btn-sm btn-primary rounded-circle" style="width: 35px; height: 35px;"><i class="bi bi-cart-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .catalog-banner {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
        margin-bottom: 30px;
    }
    .elec-cat-pill {
        background: white; border: 1px solid #e2e8f0;
        padding: 0.6rem 1.4rem; border-radius: 50rem;
        color: #475569; font-weight: 700; text-decoration: none;
        transition: all 0.3s;
        display: inline-flex; align-items: center; gap: 0.6rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        white-space: nowrap;
    }
    .elec-cat-pill:hover, .elec-cat-pill.active {
        border-color: #1e3a8a; color: white; background: #1e3a8a;
    }
</style>
@endsection
