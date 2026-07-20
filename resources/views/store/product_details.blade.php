@extends('layouts.app')

@section('title', $product->title . ' | Details')

@section('content')
<style>
    /* ════════ PREMIUM PRODUCT DETAILS CSS ════════ */
    :root {
        --premium-bg: #f8fafc;
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.5);
        --accent-hsl: 221, 83%, 53%; /* Primary Blue */
        --success-hsl: 152, 69%, 31%; /* Deep Emerald */
    }
    body { background-color: var(--premium-bg); }
    
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
    }

    .pro-gallery-wrapper {
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        background: #fff;
        height: 100%;
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pro-gallery-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .pro-gallery-wrapper:hover img {
        transform: scale(1.05);
    }
    
    .vendor-badge-premium {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        padding: 6px 16px 6px 6px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        text-decoration: none;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    .vendor-badge-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }
    .vendor-avatar-premium {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, hsl(var(--accent-hsl)), #6366f1);
        color: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800;
    }

    .premium-title {
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: -1px;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .premium-price {
        font-size: 2rem;
        font-weight: 900;
        color: hsl(var(--accent-hsl));
        display: flex; align-items: center; gap: 12px;
    }
    
    .btn-add-premium {
        background: linear-gradient(135deg, hsl(var(--accent-hsl)), #4f46e5);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 16px 32px;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.25);
    }
    .btn-add-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(37, 99, 235, 0.35);
        color: white;
    }

    .qty-controls-premium {
        display: flex; align-items: center;
        background: white;
        border-radius: 16px;
        padding: 8px;
        border: 1px solid #e2e8f0;
    }
    .qty-btn-premium {
        width: 40px; height: 40px;
        border: none; background: #f8fafc;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #475569;
        transition: all 0.2s;
    }
    .qty-btn-premium:hover { background: #e2e8f0; color: #0f172a; }
    .qty-input-premium {
        width: 50px; border: none; text-align: center;
        font-weight: 700; font-size: 1.1rem; background: transparent;
    }
    
    .trust-features {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 30px;
    }
    .trust-item {
        text-align: center; background: white; padding: 15px 10px;
        border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .trust-icon-p { font-size: 1.8rem; margin-bottom: 8px; color: hsl(var(--accent-hsl)); }
    .trust-text-p { font-size: 0.85rem; font-weight: 700; color: #475569; line-height: 1.2; }

    .custom-nav-tabs .nav-link {
        border: none; color: #64748b; font-weight: 600; font-size: 1.1rem;
        padding: 10px 20px; border-bottom: 3px solid transparent; background: transparent;
    }
    .custom-nav-tabs .nav-link.active {
        color: hsl(var(--accent-hsl)); border-bottom-color: hsl(var(--accent-hsl)); background: transparent;
    }
    
    .review-card {
        background: white; border-radius: 16px; padding: 20px;
        border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
</style>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Marketplace</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ Str::limit($product->title, 30) }}</li>
        </ol>
    </nav>

    <!-- Main Detail Panel -->
    <div class="row g-5 mb-5">
        <!-- Image Gallery Column -->
        <div class="col-lg-6">
            <div class="pro-gallery-wrapper">
                <img src="{{ kura_product_image_url($product->image_url, 'https://placehold.co/800') }}" alt="{{ $product->title }}">
            </div>
        </div>

        <!-- Details Column -->
        <div class="col-lg-6">
            <div class="glass-card p-4 p-md-5">
                <!-- Vendor Chip -->
                <a href="#" class="vendor-badge-premium mb-4">
                    <div class="vendor-avatar-premium">
                        {{ strtoupper(substr($product->vendor->shop_name ?? 'V', 0, 1)) }}
                    </div>
                    <span>{{ $product->vendor->shop_name ?? 'Verified Seller' }}</span>
                    @if($product->vendor->is_verified ?? false)
                        <i class="bi bi-patch-check-fill text-primary" title="Verified Vendor"></i>
                    @endif
                </a>

                <h1 class="premium-title">{{ $product->title }}</h1>

                <!-- Reviews overview -->
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="text-warning fs-5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $avgRating ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                    <span class="text-muted fw-semibold">({{ $reviewCount }} reviews)</span>
                </div>

                <div class="premium-price mb-4">
                    {{ number_format($product->price) }} RWF
                </div>

                <p class="text-secondary fs-6 mb-4" style="line-height: 1.7;">{{ $product->description }}</p>

                <!-- Stock indicators -->
                <div class="mb-4">
                    @if($product->stock_quantity > 0)
                        <span class="badge bg-success-subtle text-success fs-6 px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill"></i> In Stock: {{ $product->stock_quantity }} {{ $product->price_unit ?? 'units' }}</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>
                    @endif
                </div>

                <!-- Action Section -->
                @if($product->stock_quantity > 0)
                    <div class="row g-3 mb-4">
                        <div class="col-auto">
                            <div class="qty-controls-premium">
                                <button type="button" class="qty-btn-premium" onclick="decrementQty()"><i class="bi bi-dash"></i></button>
                                <input type="number" id="purchase-qty" class="qty-input-premium" value="1" min="1" max="{{ $product->stock_quantity }}">
                                <button type="button" class="qty-btn-premium" onclick="incrementQty()"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                        <div class="col">
                            <button type="button" onclick="addDetailedToCart({{ $product->id }})" class="btn-add-premium">
                                <i class="bi bi-bag-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Contact Vendor direct widgets -->
                <div class="d-flex gap-3 mt-2">
                    @if(!empty($product->vendor->phone))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $product->vendor->phone) }}" target="_blank" class="btn btn-light border rounded-pill fw-bold px-4 py-2 text-dark flex-grow-1 d-flex align-items-center justify-content-center gap-2" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                            <i class="bi bi-whatsapp text-success fs-5"></i> Chat
                        </a>
                    @endif
                    @if(!empty($product->vendor->phone))
                        <a href="tel:{{ $product->vendor->phone }}" class="btn btn-light border rounded-pill fw-bold px-4 py-2 text-dark flex-grow-1 d-flex align-items-center justify-content-center gap-2" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                            <i class="bi bi-telephone text-primary fs-5"></i> Call
                        </a>
                    @endif
                </div>

                <!-- Trust Badges -->
                <div class="trust-features">
                    <div class="trust-item">
                        <div class="trust-icon-p"><i class="bi bi-shield-check"></i></div>
                        <div class="trust-text-p">Secure<br>Payments</div>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon-p"><i class="bi bi-box-seam"></i></div>
                        <div class="trust-text-p">Fast<br>Delivery</div>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon-p"><i class="bi bi-award"></i></div>
                        <div class="trust-text-p">Quality<br>Guaranteed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Information -->
    <div class="glass-card p-4 p-md-5 mb-5">
        <ul class="nav custom-nav-tabs border-bottom mb-4" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Specifications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews ({{ $reviewCount }})</button>
            </li>
        </ul>
        <div class="tab-content" id="productTabsContent">
            <!-- Details Tab -->
            <div class="tab-pane fade show active" id="details" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <tbody>
                            <tr>
                                <th class="w-25 text-muted fw-semibold border-0 py-3">Category</th>
                                <td class="fw-bold border-0 py-3">{{ $product->category }}</td>
                            </tr>
                            @if($product->batch_number)
                                <tr>
                                    <th class="text-muted fw-semibold py-3 border-top">Batch Number</th>
                                    <td class="fw-bold py-3 border-top">{{ $product->batch_number }}</td>
                                </tr>
                            @endif
                            @if($product->harvest_date)
                                <tr>
                                    <th class="text-muted fw-semibold py-3 border-top">Harvest Date</th>
                                    <td class="fw-bold py-3 border-top">{{ $product->harvest_date }}</td>
                                </tr>
                            @endif
                            @if($product->expiry_date)
                                <tr>
                                    <th class="text-muted fw-semibold py-3 border-top">Expiry Date</th>
                                    <td class="fw-bold py-3 border-top">{{ $product->expiry_date }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <!-- System Notifications -->
                @if(session('review_success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('review_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('review_error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('review_error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Leave review block -->
                @if($hasPurchased)
                    <div class="review-card mb-4 border-primary" style="background: rgba(37, 99, 235, 0.03);">
                        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-pencil-square"></i> Share Your Experience</h5>
                        <form action="{{ route('products.review', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Your Rating</label>
                                <select name="rating" class="form-select rounded-3" style="max-width: 250px;" required>
                                    <option value="5">⭐⭐⭐⭐⭐ 5 Stars</option>
                                    <option value="4">⭐⭐⭐⭐ 4 Stars</option>
                                    <option value="3">⭐⭐⭐ 3 Stars</option>
                                    <option value="2">⭐⭐ 2 Stars</option>
                                    <option value="1">⭐ 1 Star</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Review Description</label>
                                <textarea name="review_text" class="form-control rounded-4 p-3" rows="3" placeholder="Tell other buyers about this product..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Submit Review</button>
                        </form>
                    </div>
                @endif

                <!-- Reviews list -->
                @if($reviews->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-chat-square-text text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 fw-semibold">No reviews recorded yet for this product.</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($reviews as $review)
                            <div class="review-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="vendor-avatar-premium" style="width: 40px; height: 40px; background: #e2e8f0; color: #475569;">
                                            {{ strtoupper(substr($review->full_name, 0, 1)) }}
                                        </div>
                                        <h6 class="fw-bold m-0">{{ $review->full_name }}</h6>
                                    </div>
                                    <span class="text-muted small fw-semibold">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                </div>
                                <div class="text-warning mb-3 ms-5 ps-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-secondary m-0 ms-5 ps-1">"{{ $review->review_text }}"</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related products showcase -->
    @if($related->isNotEmpty())
        <div class="mb-5">
            <h3 class="fw-bold mb-4">You might also like</h3>
            <div class="row g-4">
                @foreach($related as $p)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('products.show', $p->id) }}" class="text-decoration-none">
                            <div class="card border-0 h-100 bg-transparent" style="transition: transform 0.3s;">
                                <div class="rounded-4 overflow-hidden mb-3" style="aspect-ratio: 1; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                    <img src="{{ kura_product_image_url($p->image_url, 'https://placehold.co/400') }}" alt="{{ $p->title }}" class="w-100 h-100 object-fit-contain p-3" style="transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                </div>
                                <span class="text-primary small fw-bold text-uppercase tracking-wider">{{ $p->category }}</span>
                                <h6 class="fw-bold mt-2 text-dark">{{ Str::limit($p->title, 40) }}</h6>
                                <div class="fw-900 text-dark fs-5 mt-auto">{{ number_format($p->price) }} RWF</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    function incrementQty() {
        const input = document.getElementById('purchase-qty');
        const max = parseInt(input.getAttribute('max'));
        let val = parseInt(input.value);
        if(val < max) {
            input.value = val + 1;
        }
    }

    function decrementQty() {
        const input = document.getElementById('purchase-qty');
        let val = parseInt(input.value);
        if(val > parseInt(input.getAttribute('min'))) {
            input.value = val - 1;
        }
    }

    function addDetailedToCart(productId) {
        const qty = document.getElementById('purchase-qty').value;
        if(typeof addToCart === 'function') {
            addToCart(productId, null, qty);
        } else {
            alert('Cart system not initialized properly.');
        }
    }
</script>
@endsection
