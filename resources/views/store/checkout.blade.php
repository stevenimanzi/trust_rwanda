@extends('layouts.app')

@section('title', 'Checkout | Trust Rwanda')

@section('content')
<div class="checkout-header text-white pt-5 pb-4 mb-5">
    <div class="container">
        <h1 class="fw-extrabold m-0">Secure Checkout</h1>
        <p class="lead mt-2 mb-0" style="opacity: 0.9;">Complete your purchase and arrange delivery</p>
    </div>
</div>

<div class="container pb-5 mb-5">
    <div class="row g-5">
        <!-- Form Fields (Left Column) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-5" style="border-radius: 24px;">
                <h3 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt-fill text-primary"></i> Delivery Details
                </h3>
                
                <form id="checkoutForm" action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <label class="form-label fw-bold text-dark mb-0">Delivery Address</label>
                            <button type="button" id="shareLocationBtn" class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-1 fw-bold">
                                <i class="bi bi-geo-alt-fill"></i> Pin Location
                            </button>
                        </div>
                        <textarea name="address" class="form-control" rows="3" placeholder="e.g. KN 78 St, Kigali, House #12" style="border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px;" required>{{ $user->address }}</textarea>
                        <div id="locationStatus" class="small mt-2 fw-bold"></div>
                        
                        <input type="hidden" name="location_lat" id="location_lat">
                        <input type="hidden" name="location_lng" id="location_lng">
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold text-dark">Phone Number (Delivery Contact)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;"><i class="bi bi-telephone-fill"></i></span>
                            <input type="text" name="contact_phone" class="form-control border-start-0 ps-0" value="{{ $user->phone }}" style="border-radius: 0 12px 12px 0; background: #f8fafc; border: 1px solid #e2e8f0; height: 50px;" required>
                        </div>
                        <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle text-primary"></i> Preferably a WhatsApp number for delivery notifications.</small>
                    </div>

                    <hr class="mb-5 border-light">

                    <h3 class="fw-bold mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-credit-card-fill text-primary"></i> Secure Payment
                    </h3>

                    @php
                        $vendorCount = count($vendorGroups);
                    @endphp

                    <div class="payment-alert-box p-4 mb-4 position-relative overflow-hidden" style="border-radius: 16px; background: #f8fafc; border: 2px solid #e2e8f0;">
                        <!-- Background Icon -->
                        <i class="bi bi-shield-lock-fill position-absolute text-primary" style="font-size: 8rem; right: -20px; top: -20px; opacity: 0.05;"></i>
                        
                        <div class="d-flex align-items-start gap-3 position-relative z-index-1">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0;">
                                <i class="bi bi-credit-card-2-front-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-extrabold text-primary mb-2">Online Payment</h5>
                                @if($vendorCount > 1)
                                    <p class="text-dark fw-bold mb-1">You have items from {{ $vendorCount }} different vendors!</p>
                                    <p class="text-muted small m-0">You will be securely redirected to Pesapal to complete a single payment for all items. We will handle splitting the payment to the respective vendors automatically.</p>
                                @else
                                    <p class="text-muted small m-0">You will be securely redirected to Pesapal to complete your payment via Mobile Money (MTN Momo/Airtel) or Card.</p>
                                @endif
                                
                                <div class="d-flex align-items-center gap-2 mt-3 opacity-75">
                                    <span class="badge" style="background-color: #ffcc00; color: #000; font-size: 0.75rem;">MTN MoMo</span>
                                    <span class="badge" style="background-color: #ff0000; color: #fff; font-size: 0.75rem;">Airtel Money</span>
                                    <span class="badge bg-secondary" style="font-size: 0.75rem;"><i class="bi bi-credit-card-fill me-1"></i> Card</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-3 rounded-pill fw-extrabold shadow" style="font-size: 1.1rem; transition: transform 0.2s;">
                        Pay Now <i class="bi bi-shield-check ms-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Cart Summary (Right Column) -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="border-radius: 24px; top: 120px;">
                <h4 class="fw-bold text-dark mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
                    <span>Order Summary</span>
                    <span class="badge bg-primary rounded-pill">{{ count($vendorGroups) }} Shop{{ $vendorCount > 1 ? 's' : '' }}</span>
                </h4>
                
                <div class="d-flex flex-column gap-4 mb-4">
                    @foreach($vendorGroups as $vId => $group)
                        <div class="vendor-group-box">
                            <div class="d-flex align-items-center gap-2 mb-3 px-2">
                                <i class="bi bi-shop text-primary fs-5"></i>
                                <span class="fw-bold text-dark text-uppercase" style="letter-spacing: 0.5px; font-size: 0.85rem;">{{ $group['shop_name'] }}</span>
                            </div>
                            
                            <div class="bg-light p-3" style="border-radius: 16px;">
                                @foreach($group['items'] as $item)
                                    @php $p = $item['product']; @endphp
                                    <div class="d-flex justify-content-between align-items-center mb-3 last-mb-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="width: 50px; height: 50px; background: white; border-radius: 10px; overflow: hidden; flex-shrink: 0; border: 1px solid #e2e8f0;">
                                                <img src="{{ kura_product_image_url($p->image_url, 'https://placehold.co/50') }}" class="w-100 h-100 object-fit-cover" alt="{{ $p->title }}">
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark m-0 text-truncate" style="max-width: 150px; font-size: 0.9rem;">{{ $p->title }}</h6>
                                                <small class="text-muted fw-semibold">Qty: {{ $item['qty'] }} &times; {{ number_format($p->price) }} RWF</small>
                                            </div>
                                        </div>
                                        <div class="fw-bold text-end" style="font-size: 0.95rem;">
                                            {{ number_format($p->price * $item['qty']) }} RWF
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 px-2">
                                <span class="small fw-bold text-muted">Shop Subtotal</span>
                                <span class="fw-bold text-dark">{{ number_format($group['subtotal']) }} RWF</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 bg-primary-subtle mt-2" style="border-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-muted">Total Items Cost</span>
                        <span class="fw-bold text-dark">{{ number_format($total) }} RWF</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-muted">Delivery Fee</span>
                        <span class="fw-bold text-success"><i class="bi bi-info-circle me-1"></i> TBD with Vendor</span>
                    </div>
                    <hr class="border-primary opacity-25">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fw-extrabold text-dark fs-5">Grand Total</span>
                        <span class="fw-extrabold fs-4 text-primary">{{ number_format($total) }} RWF</span>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="small text-muted fw-semibold"><i class="bi bi-shield-lock-fill text-success me-1"></i> 256-bit Secure Checkout</p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<style>
    .vendor-group-box .last-mb-0:last-child { margin-bottom: 0 !important; }
    #submitBtn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(30,58,138,0.2) !important; }
</style>
<script>
const shareLocationBtn = document.getElementById('shareLocationBtn');
const locationStatus = document.getElementById('locationStatus');
const addressField = document.querySelector('textarea[name="address"]');
const locationLatField = document.getElementById('location_lat');
const locationLngField = document.getElementById('location_lng');

if (shareLocationBtn && addressField) {
    shareLocationBtn.addEventListener('click', () => {
        if (!navigator.geolocation) {
            locationStatus.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Location sharing is not supported by your browser.</span>';
            return;
        }

        shareLocationBtn.disabled = true;
        shareLocationBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Pinning...';
        locationStatus.innerHTML = '<span class="text-muted">Requesting device coordinates...</span>';

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);
                
                locationLatField.value = lat;
                locationLngField.value = lng;
                
                try {
                    locationStatus.innerHTML = '<span class="text-muted"><span class="spinner-border spinner-border-sm me-1"></span> Resolving address...</span>';
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                    const data = await response.json();
                    
                    if (data && data.display_name) {
                        const currentAddress = addressField.value.trim();
                        // If empty or if it looks like they haven't typed anything meaningful, replace it
                        if (!currentAddress || currentAddress.includes('e.g. KN')) {
                            addressField.value = data.display_name;
                        } else {
                            addressField.value = `${currentAddress}\n${data.display_name}`;
                        }
                    }
                } catch (e) {
                    console.warn("Reverse geocoding failed", e);
                }

                locationStatus.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Location pinned & mapped successfully!</span>';
                shareLocationBtn.disabled = false;
                shareLocationBtn.innerHTML = '<i class="bi bi-geo-alt-fill me-1"></i> Update location';
            },
            (error) => {
                let message = 'Unable to access coordinates.';
                if (error.code === 1) {
                    message = 'Location permission denied by user.';
                }
                locationStatus.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> ${message}</span>`;
                shareLocationBtn.disabled = false;
                shareLocationBtn.innerHTML = '<i class="bi bi-geo-alt-fill me-1"></i> Share live location';
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
        );
    });
}

document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('submitBtn');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing order...';

    try {
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.status === 'success') {
            if (result.redirect_url) {
                window.location.href = result.redirect_url;
            } else {
                window.location.href = "{{ route('order.success') }}";
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: result.message,
                confirmButtonColor: 'var(--primary)',
                customClass: {
                    popup: 'rounded-4'
                }
            });
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Connectivity Error',
            text: error.message,
            confirmButtonColor: 'var(--primary)',
            customClass: {
                popup: 'rounded-4'
            }
        });
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});
</script>
@endsection
@endsection
