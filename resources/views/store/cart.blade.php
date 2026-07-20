@extends('layouts.app')

@section('title', 'My Bag | Trust Rwanda')

@section('content')
<style>
    /* Premium Cart Styles */
    .cart-wrapper {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    }
    
    .cart-header-title {
        font-weight: 800;
        font-size: 2.2rem;
        color: #0f172a;
        letter-spacing: -1px;
    }

    .cart-item-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 24px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .cart-item-card:hover {
        border-color: #e2e8f0;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .product-img-container {
        width: 120px;
        height: 120px;
        flex-shrink: 0;
        background: #f8fafc;
        border-radius: 16px;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
    }

    /* Qty Switcher */
    .qty-switcher {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 50px;
        padding: 4px;
        border: 1px solid #e2e8f0;
    }
    .qty-btn {
        background: #ffffff;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .qty-btn:hover {
        background: #3b82f6;
        color: white;
    }
    .qty-input {
        width: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
    }
    .qty-input:focus {
        outline: none;
    }

    /* Remove Button */
    .remove-btn {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }
    .remove-btn:hover {
        background: #ef4444;
        color: white;
        transform: scale(1.05) rotate(5deg);
    }

    /* Summary Card */
    .summary-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 28px;
        padding: 30px;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 100px;
    }
    .summary-title {
        font-weight: 800;
        font-size: 1.5rem;
        color: #0f172a;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-checkout {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        font-weight: 700;
        padding: 16px;
        border-radius: 16px;
        border: none;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(59, 130, 246, 0.4);
        color: white;
    }
    
    .price-tag {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.25rem;
    }

    .empty-state {
        padding: 60px 20px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 30px;
        border: 1px dashed #cbd5e1;
    }
    
    @media (max-width: 768px) {
        .product-img-container { width: 90px; height: 90px; }
        .cart-header-title { font-size: 1.8rem; }
    }
</style>

<div class="container cart-wrapper my-5">
    <div class="row g-5 justify-content-center">
        <!-- Cart Items List -->
        <div class="col-xl-8 col-lg-7">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h2 class="cart-header-title m-0">My Bag</h2>
                    <p class="text-muted fw-medium mt-1 mb-0"><span id="cart-count-title" class="text-primary fw-bold">{{ count($cart) }}</span> items in your bag</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill fw-bold text-primary px-4 shadow-sm border-0 d-none d-sm-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Continue Shopping
                </a>
            </div>

            @if($products->isEmpty())
                <div class="empty-state text-center shadow-sm">
                    <div class="mb-4">
                        <i class="bi bi-bag-x" style="font-size: 5rem; color: #cbd5e1;"></i>
                    </div>
                    <h3 class="fw-800 text-dark mb-3">Your Bag is Empty</h3>
                    <p class="text-muted mb-4 px-md-5">Looks like you haven't added anything to your Trust Rwanda bag yet. Discover top products from verified local vendors.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow">
                        <i class="bi bi-shop me-2"></i> Explore Marketplace
                    </a>
                </div>
            @else
                <div class="d-flex flex-column gap-4">
                    @foreach($products as $product)
                        @php $qty = $cart[$product->id] ?? 1; @endphp
                        <div class="cart-item-card p-4" id="cart-row-{{ $product->id }}">
                            <div class="row g-3 g-md-4 align-items-center">
                                
                                <!-- Image -->
                                <div class="col-auto">
                                    <div class="product-img-container">
                                        <img src="{{ kura_product_image_url($product->image_url, 'https://placehold.co/200') }}" alt="{{ $product->title }}" class="w-100 h-100 object-fit-contain">
                                    </div>
                                </div>
                                
                                <!-- Details -->
                                <div class="col min-vw-0" style="min-width: 0;">
                                    <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small mb-2 text-uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.7rem;">
                                        {{ $product->category }}
                                    </div>
                                    <h5 class="fw-800 text-dark mb-1 text-truncate">{{ $product->title }}</h5>
                                    <div class="d-flex align-items-center gap-2 mb-3 mb-md-0">
                                        <i class="bi bi-shop text-muted"></i>
                                        <span class="small fw-medium text-muted">{{ $product->vendor->shop_name ?? 'Trust Rwanda Vendor' }}</span>
                                    </div>
                                </div>

                                <!-- Actions (Qty, Price, Delete) -->
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-between gap-3 gap-md-4">
                                    <!-- Qty switcher -->
                                    <div class="qty-switcher flex-shrink-0">
                                        <button type="button" class="qty-btn" onclick="updateCartQty({{ $product->id }}, -1)">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>
                                        <input type="text" id="qty-{{ $product->id }}" class="qty-input" value="{{ $qty }}" readonly>
                                        <button type="button" class="qty-btn" onclick="updateCartQty({{ $product->id }}, 1)">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="text-end min-vw-0">
                                        <div class="price-tag"><span id="sub-{{ $product->id }}">{{ number_format($product->price * $qty) }}</span> RWF</div>
                                        <small class="text-muted fw-medium">{{ number_format($product->price) }} RWF / each</small>
                                    </div>
                                    
                                    <div class="ps-md-2 border-start-md border-0 flex-shrink-0">
                                        <button type="button" onclick="removeFromCart({{ $product->id }})" class="remove-btn shadow-sm" title="Remove Item">
                                            <i class="bi bi-trash3-fill fs-5"></i>
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sticky Summary Side -->
        @if(!$products->isEmpty())
            <div class="col-xl-4 col-lg-5">
                <div class="summary-card">
                    <h4 class="summary-title">
                        <i class="bi bi-receipt text-primary"></i> Order Summary
                    </h4>
                    
                    <div class="d-flex justify-content-between mb-3 fs-6">
                        <span class="text-muted fw-medium">Subtotal ({{ count($cart) }} items)</span>
                        <span class="fw-bold text-dark"><span id="cart-subtotal">{{ number_format($total) }}</span> RWF</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 pb-4 border-bottom border-light-subtle fs-6">
                        <span class="text-muted fw-medium">Delivery Fee</span>
                        <span class="text-success fw-bold bg-success bg-opacity-10 px-2 py-1 rounded">Calculated at Checkout</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <span class="fs-5 fw-bold text-dark">Estimated Total</span>
                        <div class="text-end">
                            <div class="fs-3 fw-900 text-primary lh-1" style="letter-spacing: -0.5px;">
                                <span id="cart-total">{{ number_format($total) }}</span> RWF
                            </div>
                            <small class="text-muted fw-medium">VAT included</small>
                        </div>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" class="btn btn-checkout w-100">
                        Proceed to Checkout <i class="bi bi-shield-lock ms-2"></i>
                    </a>
                    
                    <div class="mt-4 pt-3 border-top text-center">
                        <p class="small text-muted fw-medium mb-2"><i class="bi bi-credit-card-2-front me-2"></i>Secure Payment Options</p>
                        <div class="d-flex justify-content-center gap-2 opacity-75">
                            <span class="badge bg-light text-dark border p-2"><i class="bi bi-phone"></i> Mobile Money</span>
                            <span class="badge bg-light text-dark border p-2"><i class="bi bi-whatsapp"></i> Direct Order</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
    function updateCartQty(productId, change) {
        const input = document.getElementById('qty-' + productId);
        let val = parseInt(input.value) + change;
        if(val < 1) return;

        // Visual feedback
        input.value = val;

        fetch("{{ route('cart.update') }}", {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_id: productId, qty: val })
        })
        .then(r => r.json())
        .then(data => {
            if(data.status === 'success') {
                window.location.reload();
            } else {
                showToast(data.message, 'error');
                input.value = val - change; // rollback visually
            }
        });
    }

    function removeFromCart(productId) {
        const row = document.getElementById('cart-row-' + productId);
        // Visual exit animation
        row.style.transform = 'scale(0.95)';
        row.style.opacity = '0';
        row.style.transition = 'all 0.3s ease';

        setTimeout(() => {
            fetch("{{ route('cart.remove') }}", {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    window.location.reload();
                } else {
                    showToast("Failed to remove item", 'error');
                    row.style.transform = 'scale(1)';
                    row.style.opacity = '1';
                }
            });
        }, 300);
    }
</script>
@endsection
@endsection
