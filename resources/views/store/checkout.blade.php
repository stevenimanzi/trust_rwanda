@extends('layouts.app')

@section('title', 'Checkout | Trust Rwanda')

@section('content')
<div class="container py-5">
    <div class="row g-5">
        <!-- Form Fields -->
        <div class="col-lg-7">
            <div class="pro-card p-4">
                <h2 class="checkout-title mb-4">Checkout Details</h2>
                
                <form id="checkoutForm" action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                    
                    <span class="section-label">1. Delivery Location</span>
                    <div class="mb-4">
                        <div class="location-inline-row mb-2">
                            <label class="form-label fw-bold text-dark mb-0">Delivery Address</label>
                            <button type="button" id="shareLocationBtn" class="btn btn-location d-flex align-items-center gap-1">
                                <i class="bi bi-geo-alt-fill"></i> Pin Device Location
                            </button>
                        </div>
                        <textarea name="address" class="form-control form-control-pro" rows="3" placeholder="e.g. KN 78 St, Kigali, House #12" required>{{ $user->address }}</textarea>
                        <div id="locationStatus" class="small mt-1 text-muted"></div>
                        
                        <input type="hidden" name="location_lat" id="location_lat">
                        <input type="hidden" name="location_lng" id="location_lng">
                    </div>

                    <span class="section-label">2. Contact Information</span>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Phone Number (delivery contact)</label>
                        <input type="text" name="contact_phone" class="form-control form-control-pro" value="{{ $user->phone }}" required>
                        <small class="text-muted">Preferably a WhatsApp number for delivery notifications.</small>
                    </div>

                    <div class="wa-badge-box mt-4 d-flex align-items-start gap-3">
                        <div class="fs-3 text-success"><i class="bi bi-whatsapp"></i></div>
                        <div>
                            <h6 class="fw-bold text-success mb-1">WhatsApp Order Processing</h6>
                            <p class="small text-secondary m-0">After order placement, we will generate direct WhatsApp order invoice links for each vendor in your cart so you can chat with them directly.</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="submitBtn" class="btn-checkout w-100">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cart Summary Column -->
        <div class="col-lg-5">
            <div class="pro-card p-4">
                <h4 class="fw-bold text-dark mb-4">Cart Summary</h4>
                
                <div class="d-flex flex-column gap-3 mb-4">
                    @foreach($cartItems as $item)
                        @php $p = $item['product']; @endphp
                        <div class="product-list-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="thumb-wrapper" style="width: 50px; height: 50px; background: #f8fafc; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                                    <img src="{{ $p->image_url ? asset($p->image_url) : 'https://placehold.co/50' }}" class="w-100 h-100 object-fit-contain p-1" alt="{{ $p->title }}">
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark m-0 text-truncate" style="max-width: 180px;">{{ $p->title }}</h6>
                                    <small class="text-muted">{{ $item['qty'] }}x @ {{ number_format($p->price) }} RWF</small>
                                </div>
                            </div>
                            <div class="fw-bold text-end">
                                {{ number_format($p->price * $item['qty']) }} RWF
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-3 bg-light rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-muted">Subtotal</span>
                        <span class="small fw-bold">{{ number_format($total) }} RWF</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="small fw-bold text-muted">Delivery</span>
                        <span class="small fw-bold text-success">Pay to Vendor</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="fw-bold text-dark">Total</span>
                        <span class="fw-bold fs-4 text-danger">{{ number_format($total) }} RWF</span>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="small text-muted"><i class="bi bi-lock-fill me-1"></i> Secure end-to-end transaction</p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
const shareLocationBtn = document.getElementById('shareLocationBtn');
const locationStatus = document.getElementById('locationStatus');
const addressField = document.querySelector('textarea[name="address"]');
const locationLatField = document.getElementById('location_lat');
const locationLngField = document.getElementById('location_lng');

if (shareLocationBtn && addressField) {
    shareLocationBtn.addEventListener('click', () => {
        if (!navigator.geolocation) {
            locationStatus.innerHTML = '<span class="text-danger">Location sharing is not supported in this browser.</span>';
            return;
        }

        shareLocationBtn.disabled = true;
        shareLocationBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Pinning...';
        locationStatus.innerHTML = 'Requesting device coordinates...';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);
                const locationNote = `Live location: ${lat}, ${lng} (shared from device)`;
                const currentAddress = addressField.value.trim();
                addressField.value = currentAddress ? `${currentAddress}\n${locationNote}` : locationNote;
                locationLatField.value = lat;
                locationLngField.value = lng;
                locationStatus.innerHTML = '<span class="text-success">Live location pinned successfully!</span>';
                shareLocationBtn.disabled = false;
                shareLocationBtn.innerHTML = '<i class="bi bi-geo-alt-fill me-1"></i> Share live location';
            },
            (error) => {
                let message = 'Unable to access coordinates.';
                if (error.code === 1) {
                    message = 'Location permission denied.';
                }
                locationStatus.innerHTML = `<span class="text-danger">${message}</span>`;
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
            window.location.href = "{{ route('order.success') }}";
        } else {
            alert("Oops: " + result.message);
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        alert("Connectivity error: " + error.message);
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});
</script>
@endsection
@endsection
