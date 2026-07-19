@php
    $currentUser = auth()->user();
    $userRole = $currentUser ? $currentUser->role : 'guest';
    $isDashboardUser = $currentUser && ($userRole === 'admin' || $userRole === 'vendor') && (request()->is('admin*') || request()->is('vendor*'));

    try {
        $footerCategories = \App\Models\Category::take(6)->get();
    } catch (\Exception $e) {
        $footerCategories = collect();
    }

    $sysSettings = \App\Models\SystemSetting::pluck('setting_value', 'setting_key')->toArray();
    $siteName = $sysSettings['site_name'] ?? 'Trust Rwanda';
    $siteLogo = !empty($sysSettings['site_logo']) ? asset($sysSettings['site_logo']) : asset('assets/uploads/logos/TrustRwanda-Logo.jpg');
    $supportPhone = $sysSettings['support_phone'] ?? '0780000000';
    $supportEmail = $sysSettings['support_email'] ?? 'stivenimanzi1@gmail.com';
    $cleanPhone = preg_replace('/[^0-9]/', '', $supportPhone);
    if (strpos($cleanPhone, '0') === 0 && strlen($cleanPhone) === 10) {
        $cleanPhone = '250' . substr($cleanPhone, 1);
    }
@endphp

<style>
    /* ════════ DASHBOARD FOOTER FIX ════════ */
    .dashboard-footer {
        background: white;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 1.25rem 2rem;
        transition: margin-left 0.3s ease;
    }
    @media (min-width: 992px) {
        .dashboard-footer {
            margin-left: 280px; 
        }
    }

    /* ════════ PUBLIC MEGASTORE FOOTER ════════ */
    .mega-footer {
        background-color: #0f172a; /* Rich Slate Dark */
        color: #94a3b8;
        padding-top: 5rem;
        padding-bottom: 2rem;
        margin-top: 4rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .mega-footer-heading {
        color: #ffffff;
        font-weight: 800;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }
    .mega-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .mega-footer-links li {
        margin-bottom: 0.8rem;
    }
    .mega-footer-links a {
        color: #94a3b8;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        font-size: 0.95rem;
    }
    .mega-footer-links a:hover {
        color: #3b82f6; /* Megastore Blue */
        transform: translateX(5px);
    }
    .mega-newsletter .form-control {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        border-radius: 8px 0 0 8px;
    }
    .mega-newsletter .form-control:focus {
        background: rgba(255,255,255,0.1);
        border-color: #3b82f6;
        box-shadow: none;
    }
    .mega-newsletter .btn {
        border-radius: 0 8px 8px 0;
        background: #3b82f6;
        border-color: #3b82f6;
        font-weight: 700;
    }
    .mega-newsletter .btn:hover {
        background: #2563eb;
    }
    .mega-footer-bottom {
        border-top: 1px solid rgba(255,255,255,0.08);
        margin-top: 3rem;
        padding-top: 1.5rem;
        font-size: 0.85rem;
    }

    /* ════════ PREMIUM BRANDING ADDITIONS ════════ */
    .footer-logo-img {
        height: 55px;
        border-radius: 12px;
        margin-bottom: 20px;
        transition: 0.4s;
    }
    .footer-logo-img:hover {
        transform: scale(1.05);
    }
    
    /* ════════ REAL-TIME NOTIFICATIONS (TOASTS) ════════ */
    #toastStack { position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
    .toasty { 
        background: #1e293b; color: white; padding: 14px 22px; border-radius: 12px; 
        display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
        animation: fadeUp 0.4s ease forwards; border-left: 4px solid #3b82f6; 
        min-width: 280px;
    }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div id="toastStack"></div>

@if ($isDashboardUser)
    <footer class="dashboard-footer mt-auto">
        <div class="container-fluid px-0">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between small">
                <div class="text-muted mb-2 mb-md-0 fw-medium">
                    &copy; {{ date('Y') }} {{ $siteName }}. All Rights Reserved.
                </div>
                <div class="text-muted">
                    Designed and Developed by <a href="https://stevenimanzi.kesug.com" target="_blank" class="text-decoration-none fw-bold text-primary">Steven IMANZI</a>
                </div>
            </div>
        </div>
    </footer>
@else
    <footer class="mega-footer">
        <div class="container">
            <div class="row g-5">
                
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('home') }}">
                        <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="footer-logo-img shadow-lg">
                    </a>
                    
                    <h5 class="fw-bolder mb-3 text-white d-flex align-items-center">
                        <i class="bi bi-shop-window me-2 text-primary fs-3"></i>{{ $siteName }}
                    </h5>
                    <p class="small mb-4 pe-md-4" style="line-height: 1.7;">
                        Trust Rwanda is Rwanda's premier digital marketplace connecting local merchants, farmers, and property owners with buyers nationwide. Buy fresh produce, explore real estate, and trade quality products seamlessly.
                    </p>
                    
                    <div class="border-start border-primary ps-3 border-3">
                        <div class="small text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Architect & Developer</div>
                        <div class="fw-bolder text-white fs-6 mt-1">Steven IMANZI</div>
                        <div class="small text-primary fw-medium">CEO, IMANZI Labs</div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="mega-footer-heading">Top Categories</h6>
                    <ul class="mega-footer-links">
                        @foreach ($footerCategories as $footerCategory)
                            <li>
                                <a href="{{ route('products.index', ['category' => $footerCategory->slug]) }}">
                                    {{ $footerCategory->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="mega-footer-heading">Useful Links</h6>
                    <ul class="mega-footer-links">
                        <li><a href="{{ route('login') }}">Vendor Login</a></li>
                        <li><a href="{{ route('vendor_register') }}">Become a Seller</a></li>
                        <li><a href="{{ route('cart.index') }}">Track My Order</a></li>
                        <li><a href="mailto:{{ $supportEmail }}">Contact Support</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
                    <h6 class="mega-footer-heading">Join Our Newsletter</h6>
                    <p class="small mb-3" style="line-height: 1.6;">Stay updated with our latest products, harvests, and property deals.</p>
                    
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="mega-newsletter">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="subscribe_email" class="form-control px-3" placeholder="Enter your email address" required>
                            <button type="submit" class="btn btn-primary px-4">Subscribe</button>
                        </div>
                        @if(session('newsletter_msg'))
                            <div class="small mt-2 fw-bold text-success">{!! session('newsletter_msg') !!}</div>
                        @endif
                    </form>

                    <div class="d-flex gap-3 mt-4">
                        <a href="https://stevenimanzi.kesug.com" target="_blank" class="text-white bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#3b82f6'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">
                            <i class="bi bi-globe"></i>
                        </a>
                        <a href="mailto:{{ $supportEmail }}" class="text-white bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#3b82f6'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">
                            <i class="bi bi-envelope"></i>
                        </a>
                        <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-white bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#10b981'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mega-footer-bottom d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div class="mb-2 mb-md-0">
                    &copy; {{ date('Y') }} <strong class="text-white">{{ $siteName }}</strong>. All Rights Reserved.
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span>System Designed and Developed by</span>
                    <strong class="text-white">Steven IMANZI</strong>
                </div>
            </div>
        </div>
    </footer>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/2.4.0/Control.FullScreen.min.js"></script>

<script>
    window.KURA_BASE_PATH = '{{ url('/') }}';
    window.KURA_I18N = {
        added_to_cart: "Item added to your Trust Rwanda cart!",
        notice: "Notice:",
        saved_wishlist: "Saved to Wishlist!",
        removed_wishlist: "Removed from Wishlist."
    };

    /* ── LIVE NOTIFICATION SYSTEM ── */
    function showToast(msg, type = 'primary') {
        let stack = document.getElementById('toastStack');
        if (!stack) {
            stack = document.createElement('div');
            stack.id = 'toastStack';
            document.body.appendChild(stack);
        }
        const t = document.createElement('div');
        t.className = 'toasty';
        const icon = type === 'error' || type === true ? 'bi-exclamation-circle-fill text-danger' : 'bi-check-circle-fill text-success';
        t.innerHTML = `<i class="bi ${icon} fs-5"></i> <span class="small fw-bold">${msg}</span>`;
        stack.appendChild(t);
        setTimeout(() => { 
            t.style.opacity = '0'; 
            t.style.transform = 'translateX(50px)';
            setTimeout(() => t.remove(), 400); 
        }, 5000);
    }

    /* ── OPTIMISTIC ADD TO CART (App-like Feel) ── */
    function addToCart(productId, btnElement = null) {
        const btn = btnElement || (window.event ? window.event.currentTarget : null);
        if (!btn || btn.disabled) return;
        
        const originalText = btn.innerHTML;
        
        // 1. OPTIMISTIC UI: Instantly change button and badge
        btn.innerHTML = '<i class="bi bi-check2"></i>';
        btn.classList.add('btn-success', 'text-white');
        btn.disabled = true;

        const badgeList = document.querySelectorAll('.cart-badge, .badge-count');
        badgeList.forEach(badge => {
            let currentCount = parseInt(badge.innerText || 0);
            badge.innerText = currentCount + 1;
            badge.style.display = 'flex';
        });

        const endpoint = "{{ route('cart.add') }}";

        fetch(endpoint, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, qty: 1 })
        })
        .then(async response => {
            const text = await response.text();
            try { return JSON.parse(text); } catch (e) { throw new Error("Server Error"); }
        })
        .then(data => {
            if(data.status === 'success') {
                const successMsg = window.KURA_I18N ? window.KURA_I18N.added_to_cart : "Item added to cart successfully!";
                showToast(successMsg);
                
                // 2. SERVER SYNC: Sync exact count
                badgeList.forEach(badge => {
                    badge.innerText = data.cart_count;
                    badge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                });
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success', 'text-white');
                    btn.disabled = false;
                }, 1500);
            } else {
                // Rollback on failure
                badgeList.forEach(badge => {
                    let currentCount = Math.max(0, parseInt(badge.innerText) - 1);
                    badge.innerText = currentCount;
                    badge.style.display = currentCount > 0 ? 'flex' : 'none';
                });
                if(data.message && data.message.toLowerCase().includes('login')) {
                    window.location.href = "{{ route('login') }}";
                } else {
                    const noticePrefix = window.KURA_I18N ? window.KURA_I18N.notice : "Notice:";
                    showToast(noticePrefix + " " + data.message, 'error');
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    /* ── TOGGLE WISHLIST ── */
    function toggleFav(productId) {
        const btn = document.querySelector('.fav-btn-' + productId);
        const endpoint = "{{ route('wishlist.toggle') }}";
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                if (btn) btn.style.color = data.action === 'added' ? '#e02b27' : '#555';
                let msg = data.action === 'added' ? "Saved to Wishlist!" : "Removed from Wishlist.";
                showToast(msg);
            }
        }).catch(err => console.error(err));
    }
</script>
