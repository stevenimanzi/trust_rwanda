@extends('layouts.app')

@section('title', 'Affiliate Program | Trust Rwanda')

@section('content')
<div class="affiliate-hero text-center">
    <div class="container px-3">
        <span class="badge rounded-pill px-3 py-1.5 small mb-3 text-uppercase fw-bold" style="letter-spacing:1px; background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.3); color: #ffffff !important;"><i class="bi bi-award me-1"></i> Trust Referrals</span>
        <h1 class="affiliate-hero-title">Trust Rwanda Affiliate Program</h1>
        <p class="affiliate-hero-sub">Share quality products and housing listings, and get up to 10% commission on every closed deal.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Affiliate Panel Card -->
            <div class="aff-card">
                <!-- Navigation Tabs -->
                <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap">
                    <a class="aff-nav-link {{ $activeTab === 'dashboard' ? 'active' : '' }}" href="{{ route('affiliate.index', ['tab' => 'dashboard']) }}">Affiliate Hub</a>
                    <a class="aff-nav-link {{ $activeTab === 'products' ? 'active' : '' }}" href="{{ route('affiliate.index', ['tab' => 'products']) }}">Products to Share</a>
                    <a class="aff-nav-link {{ $activeTab === 'how' ? 'active' : '' }}" href="{{ route('affiliate.index', ['tab' => 'how']) }}">How It Works</a>
                    <a class="aff-nav-link {{ $activeTab === 'tools' ? 'active' : '' }}" href="{{ route('affiliate.index', ['tab' => 'tools']) }}">Marketing Tools</a>
                </div>

                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    @if($activeTab === 'dashboard')
                        @if(!$isLoggedIn)
                            <!-- Guest Stats Block -->
                            <div class="text-center py-5">
                                <div class="benefit-icon"><i class="bi bi-lock"></i></div>
                                <h4 class="fw-bold text-dark mb-2">Track Your Income</h4>
                                <p class="text-muted small max-width-400 mx-auto mb-4">Please log in or register an account to view your live referral clicks, pending payouts, and approved commissions logs.</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-bold" style="background:#6366f1; border-color:#6366f1;">Log In</a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">Sign Up</a>
                                </div>
                            </div>
                        @else
                            <!-- Logged In Stats -->
                            <div class="row g-3 mb-5 text-center">
                                <div class="col-md-3">
                                    <div class="card bg-light border-0 p-3" style="border-radius: 12px;">
                                        <h6 class="text-muted fw-bold">Total Referrals</h6>
                                        <h3 class="fw-extrabold text-indigo m-0">{{ $affStats['total_referrals'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light border-0 p-3" style="border-radius: 12px;">
                                        <h6 class="text-muted fw-bold">Pending Comm.</h6>
                                        <h3 class="fw-extrabold text-warning m-0">{{ number_format($affStats['pending_comm']) }} RWF</h3>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light border-0 p-3" style="border-radius: 12px;">
                                        <h6 class="text-muted fw-bold">Approved Comm.</h6>
                                        <h3 class="fw-extrabold text-success m-0">{{ number_format($affStats['approved_comm']) }} RWF</h3>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light border-0 p-3" style="border-radius: 12px;">
                                        <h6 class="text-muted fw-bold">Paid Out</h6>
                                        <h3 class="fw-extrabold text-dark m-0">{{ number_format($affStats['paid_comm']) }} RWF</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Referral Link Copy Widget -->
                            <div class="mb-5">
                                <h6 class="fw-bold mb-2">Your Personalized Referral Link</h6>
                                <div class="input-group">
                                    <input type="text" id="refUrlField" class="form-control bg-light fw-bold" value="{{ $refLink }}" readonly>
                                    <button class="btn copy-btn" type="button" onclick="copyRefLink()"><i class="bi bi-copy"></i> Copy Link</button>
                                </div>
                                <small id="copySuccessMsg" class="text-success fw-bold mt-1 d-none">Link copied to clipboard!</small>
                            </div>

                            <!-- Commission Transaction Logs -->
                            <div class="border-top pt-4">
                                <h5 class="fw-bold mb-3">Recent Commissions logs</h5>
                                @if(empty($commissionsList) || count($commissionsList) === 0)
                                    <p class="text-muted italic">No commission logs recorded yet. Share your link to start earning!</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Buyer</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($commissionsList as $comm)
                                                    <tr>
                                                        <td class="fw-bold">{{ $comm->product->title ?? 'Product' }}</td>
                                                        <td>{{ $comm->buyer->full_name ?? 'Customer' }}</td>
                                                        <td class="fw-bold text-success">{{ number_format($comm->commission_amount) }} RWF</td>
                                                        <td>
                                                            @if($comm->status === 'pending')
                                                                <span class="badge bg-warning text-dark">Pending</span>
                                                            @elseif($comm->status === 'approved')
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-secondary">Paid</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $comm->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif

                    <!-- Products list tab -->
                    @elseif($activeTab === 'products')
                        <h5 class="fw-bold mb-3">Products to Share</h5>
                        <p class="text-muted small">Share individual products on WhatsApp. When someone checks out via your link, you receive 10% commission.</p>
                        
                        <div class="row g-4">
                            @foreach($allProducts as $p)
                                @php
                                    $pShareLink = route('products.show', ['id' => $p->id, 'ref' => $userId]);
                                    $waText = "🍎 Organic Fresh Food: *{$p->title}*\nBuy it here: " . $pShareLink;
                                @endphp
                                <div class="col-md-6">
                                    <div class="card p-3 border rounded-3 d-flex flex-row gap-3">
                                        <img src="{{ kura_product_image_url($p->image_url, 'https://placehold.co/80') }}" class="rounded" style="width: 80px; height: 80px; object-fit: contain;" alt="{{ $p->title }}">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-dark m-0">{{ $p->title }}</h6>
                                            <span class="small text-danger fw-bold d-block">{{ number_format($p->price) }} RWF</span>
                                            <span class="small text-success fw-bold d-block">Est. Commission: {{ number_format($p->price * 0.1) }} RWF</span>
                                            
                                            <div class="d-flex gap-2 mt-2">
                                                <input type="text" id="share-link-{{ $p->id }}" class="form-control form-control-sm bg-light" value="{{ $pShareLink }}" readonly style="font-size:0.75rem;">
                                                <button class="btn btn-sm btn-primary" onclick="copyProductLink({{ $p->id }})"><i class="bi bi-copy"></i></button>
                                                <a href="https://wa.me/?text={{ urlencode($waText) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    <!-- How It Works Tab -->
                    @elseif($activeTab === 'how')
                        <h4 class="fw-bold text-dark mb-4 text-center">How the Affiliate System Works</h4>
                        <div class="row g-4 text-center mt-2">
                            <div class="col-md-4">
                                <div class="benefit-icon"><i class="bi bi-person-plus"></i></div>
                                <h5 class="fw-bold text-dark">1. Join Free</h5>
                                <p class="text-secondary small">Create a free account or login to unlock your personalized affiliate links.</p>
                            </div>
                            <div class="col-md-4">
                                <div class="benefit-icon"><i class="bi bi-share"></i></div>
                                <h5 class="fw-bold text-dark">2. Share Links</h5>
                                <p class="text-secondary small">Copy your referral link and post it on WhatsApp, social media, or blogs.</p>
                            </div>
                            <div class="col-md-4">
                                <div class="benefit-icon"><i class="bi bi-cash-stack"></i></div>
                                <h5 class="fw-bold text-dark">3. Earn Commissions</h5>
                                <p class="text-secondary small">Get credited 10% of the product price when someone checks out using your link.</p>
                            </div>
                        </div>

                    <!-- Marketing Tools Tab -->
                    @elseif($activeTab === 'tools')
                        <h5 class="fw-bold mb-3">Marketing Tools</h5>
                        <p class="text-muted small">Use these templates to quickly post quality listings on WhatsApp or Facebook groups.</p>
                        
                        <div class="card p-3 mb-3 border-0 bg-light" style="border-radius:12px;">
                            <h6 class="fw-bold text-dark"><i class="bi bi-whatsapp text-success"></i> Farm Fresh Template</h6>
                            <p class="text-secondary small mb-2">🥕 Farm Fresh Foods: Get organic produce delivered fresh to your doorstep in Kigali. Order here:</p>
                            <input type="text" class="form-control bg-white mb-2" value="🥕 Farm Fresh Foods: Get organic produce delivered fresh to your doorstep in Kigali. Order here: {{ $refLink }}" readonly>
                        </div>
                        
                        <div class="card p-3 mb-3 border-0 bg-light" style="border-radius:12px;">
                            <h6 class="fw-bold text-dark"><i class="bi bi-whatsapp text-success"></i> Modern Electronics Template</h6>
                            <p class="text-secondary small mb-2">💻 Modern Electronics: Upgrade your phone or laptop with warranty from verified local shops. Buy here:</p>
                            <input type="text" class="form-control bg-white mb-2" value="💻 Modern Electronics: Upgrade your phone or laptop with warranty from verified local shops. Buy here: {{ $refLink }}" readonly>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .affiliate-hero {
        background: linear-gradient(135deg, #6366f1 0%, #312e81 100%);
        color: white; padding: 6.5rem 0 7.5rem;
    }
    .affiliate-hero-title { font-size: 2.5rem; font-weight: 900; }
    .affiliate-hero-sub { color: rgba(255,255,255,0.8); }
    .aff-card {
        background: white; border-radius: 18px; border: 1px solid #f1f5f9;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08); overflow: hidden;
        padding: 30px; margin-top: -3.5rem;
    }
    .aff-nav-link {
        color: #64748b; font-weight: 700; padding: 10px 22px; border-radius: 50px;
        transition: 0.2s; border: 1px solid transparent; background: transparent;
        text-decoration: none !important;
    }
    .aff-nav-link:hover { color: #4f46e5; background: #f8fafc; }
    .aff-nav-link.active { background: #eef2ff !important; color: #6366f1 !important; }
    .benefit-icon {
        width: 54px; height: 54px; border-radius: 50%;
        background: #eef2ff; color: #6366f1;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; margin: 0 auto 16px;
    }
    .copy-btn { background: #6366f1; color: white !important; font-weight: 700; }
    .copy-btn:hover { background: #4f46e5; }
</style>

@section('scripts')
<script>
    function copyRefLink() {
        const copyText = document.getElementById("refUrlField");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        const successMsg = document.getElementById("copySuccessMsg");
        successMsg.classList.remove("d-none");
        setTimeout(() => successMsg.classList.add("d-none"), 3000);
    }

    function copyProductLink(id) {
        const copyText = document.getElementById("share-link-" + id);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        showToast("Product link copied successfully!");
    }
</script>
@endsection
@endsection
