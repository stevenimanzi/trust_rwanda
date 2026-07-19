@extends('layouts.vendor')

@section('title', 'Marketing Hub')

@section('styles')
<style>
    .chart-panel-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #eef0f3;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        margin-bottom: 25px;
    }
    .chart-panel-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--text-dark);
    }

    .table-custom {
        margin-bottom: 0;
    }
    .table-custom thead th { 
        background: #f8fafc; color: #64748b; 
        font-weight: 700; font-size: 0.72rem; 
        text-transform: uppercase; border: none; padding: 1.1rem;
    }
    .table-custom td {
        padding: 1.1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }

    .badge-status { padding: 5px 12px; border-radius: 50px; font-weight: 800; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .badge-active { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
    .badge-none { background: #f1f5f9; color: #64748b; }

    .product-thumbnail { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; background: #f8fafc; border: 1px solid var(--border-color); }
    .discount-input-group { width: 110px; }
    
    .form-label-pro {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }
    
    .form-control-pro {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.45rem 0.75rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-dark);
        outline: none;
        text-align: center;
    }

    @media (max-width: 991px) { .desktop-only { display: none !important; } }
    @media (min-width: 992px) { .mobile-only { display: none !important; } }
</style>
@endsection

@section('content')
<div class="container-fluid p-4 p-lg-5">
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark"><i class="bi bi-megaphone text-primary me-2"></i>Marketing Hub</h2>
            <p class="text-muted m-0 small">Create special offers, discounts, and flash promotions</p>
        </div>
        <a href="https://wa.me/250796194401" target="_blank" class="btn btn-outline-success btn-sm fw-bold px-3 py-2 d-flex align-items-center gap-1.5" style="border-radius:10px;">
            <i class="bi bi-whatsapp"></i> Activation Support
        </a>
    </div>

    @if(session('msg'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('msg') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm rounded-3 mb-4"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <!-- Desktop Grid (Large screens) -->
    <div class="chart-panel-card p-0 overflow-hidden desktop-only mb-5">
        <div class="p-4 border-bottom bg-white">
            <span class="chart-panel-title">Active Store Campaigns</span>
        </div>
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Product Details</th>
                        <th class="text-center">Discount Percent</th>
                        <th class="text-center">Flash Deal</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($products->isEmpty())
                        <tr><td colspan="5" class="text-center py-5 text-muted">No products available for promotions.</td></tr>
                    @else
                        @foreach($products as $p)
                            @php
                                $status = strtolower($p->promo_status ?? 'none'); 
                                $img = $p->image_url;
                                $imageDisplay = $img ? asset('assets/uploads/products/' . $img) : 'https://placehold.co/100?text=No+Media';
                                if ($img && str_starts_with($img, 'http')) {
                                    $imageDisplay = $img;
                                }
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $imageDisplay }}" class="product-thumbnail" onerror="this.src='https://placehold.co/100?text=No+Media'">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $p->title }}</div>
                                            <small class="text-muted">{{ number_format($p->price) }} RWF</small>
                                        </div>
                                    </div>
                                </td>
                                <form method="POST" action="{{ route('vendor.promotions.submit') }}" class="m-0 p-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                                    <td class="text-center">
                                        <div class="input-group input-group-sm discount-input-group mx-auto" style="max-width:110px;">
                                            <input type="number" name="discount_percent" class="form-control form-control-pro" value="{{ $p->discount_percent ?? 0 }}" min="0" max="95">
                                            <span class="input-group-text bg-light border-start-0" style="border-radius:0 10px 10px 0; border-color:#e2e8f0; font-weight:600;">%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="is_flash_deal" value="1" {{ $p->is_flash_deal ? 'checked' : '' }} style="cursor:pointer;">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-status badge-{{ $status }}">{{ ($status == 'none') ? 'Idle' : strtoupper($status) }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3 py-1.5" style="border-radius:8px; border:none; background:var(--primary);" {{ ($status == 'pending') ? 'disabled' : '' }}>
                                            {{ ($status == 'pending') ? 'Awaiting' : 'Submit' }}
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Stack list layout -->
    <div class="mobile-only">
        @if ($products->isEmpty())
            <div class="chart-panel-card text-center py-5 text-muted">No products available for promotions.</div>
        @else
            @foreach($products as $p)
                @php
                    $status = strtolower($p->promo_status ?? 'none'); 
                    $img = $p->image_url;
                    $imageDisplay = $img ? asset('assets/uploads/products/' . $img) : 'https://placehold.co/100?text=No+Media';
                    if ($img && str_starts_with($img, 'http')) {
                        $imageDisplay = $img;
                    }
                @endphp
                <div class="chart-panel-card p-4 mb-3">
                    <form method="POST" action="{{ route('vendor.promotions.submit') }}" class="m-0 p-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $imageDisplay }}" class="product-thumbnail" onerror="this.src='https://placehold.co/100?text=No+Media'">
                                <div>
                                    <div class="fw-bold text-dark" style="font-size:0.9rem;">{{ $p->title }}</div>
                                    <div class="small text-muted">{{ number_format($p->price) }} RWF</div>
                                </div>
                            </div>
                            <span class="badge-status badge-{{ $status }}">{{ ($status == 'none') ? 'IDLE' : strtoupper($status) }}</span>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label-pro mb-1">Discount %</label>
                                <input type="number" name="discount_percent" class="form-control form-control-pro w-100" value="{{ $p->discount_percent ?? 0 }}" min="0" max="95">
                            </div>
                            <div class="col-6 text-end">
                                <label class="form-label-pro mb-1 d-block">Flash Deal</label>
                                <div class="form-check form-switch d-inline-block mt-1">
                                    <input class="form-check-input" type="checkbox" name="is_flash_deal" value="1" {{ $p->is_flash_deal ? 'checked' : '' }} style="cursor:pointer;">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold" style="border-radius:10px; border:none; background:var(--primary);" {{ ($status == 'pending') ? 'disabled' : '' }}>
                            {{ ($status == 'pending') ? 'Awaiting Approval' : 'Promote Item' }}
                        </button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
