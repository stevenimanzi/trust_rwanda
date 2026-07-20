@extends('layouts.admin')

@section('title', 'Promo Requests')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-800 m-0">PROMO_QUEUE</h4>
        <p class="text-muted small m-0">Validate and deploy vendor ad campaigns</p>
    </div>
    <span class="badge bg-indigo-subtle text-primary p-2 px-3 rounded-pill fw-800 border border-primary border-opacity-25">
        {{ $requests->total() }} PENDING
    </span>
</div>

<div class="hz-card-pro">
    <div class="table-responsive">
        <table class="table hz-table align-middle">
            <thead>
                <tr>
                    <th>Product Details</th>
                    <th>Pricing Impact</th>
                    <th>Vendor</th>
                    <th class="text-end">Command</th>
                </tr>
            </thead>
            <tbody>
                @if($requests->isEmpty())
                    <tr><td colspan="4" class="text-center py-5 opacity-50 small fw-bold">NO PENDING REQUESTS</td></tr>
                @else
                    @foreach($requests as $r)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ kura_product_image_url($r->image_url, 'https://placehold.co/100?text=KURA') }}" class="product-preview" onerror="this.src='https://placehold.co/100?text=KURA'">
                                <div>
                                    <div class="fw-800 small">{{ $r->title }}</div>
                                    @if($r->is_flash_deal)
                                        <div class="badge bg-danger rounded-pill fw-bold" style="font-size: 0.6rem;">FLASH DEAL</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small text-muted fw-bold" style="text-decoration: line-through;">{{ number_format($r->price) }} RWF</div>
                            <div class="fw-900 text-success">{{ number_format($r->price * (1 - ($r->discount_percent/100))) }} RWF</div>
                            <div class="stat-badge d-inline-block mt-1">{{ $r->discount_percent }}% OFF</div>
                        </td>
                        <td>
                            <div class="fw-800 small text-info"><i class="bi bi-shop me-1"></i>{{ $r->shop_name }}</div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $r->vendor_phone) }}" target="_blank" class="text-muted small mt-1 d-block text-decoration-none"><i class="bi bi-whatsapp"></i> Chat</a>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <form method="POST" action="{{ route('admin.promo_requests.approve') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $r->id }}">
                                    <button type="submit" class="btn btn-success border-0 rounded-pill px-3 py-2 fw-bold" style="font-size:0.8rem;"><i class="bi bi-check-circle-fill me-1"></i> Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.promo_requests.reject') }}" class="d-inline" onsubmit="return confirm('Reject promo request?');">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $r->id }}">
                                    <button type="submit" class="btn btn-light border-0 rounded-pill px-3 py-2 fw-bold text-danger" style="font-size:0.8rem;">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        @if($requests->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $requests->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <div class="mobile-list">
        @foreach ($requests as $r)
        <div class="mobile-request-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="{{ kura_product_image_url($r->image_url, 'https://placehold.co/100?text=KURA') }}" class="product-preview" onerror="this.src='https://placehold.co/100?text=KURA'">
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-900 small text-truncate">{{ $r->title }}</div>
                    <div class="text-primary fw-bold" style="font-size:0.7rem;">{{ $r->shop_name }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-900 text-success small">{{ number_format($r->price * (1 - ($r->discount_percent/100))) }}</div>
                    <div class="stat-badge">{{ $r->discount_percent }}%</div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('admin.promo_requests.approve') }}" class="flex-grow-1">
                    @csrf
                    <input type="hidden" name="id" value="{{ $r->id }}">
                    <button type="submit" class="btn btn-action btn-approve w-100 border-0"><i class="bi bi-check-circle-fill me-1"></i> Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.promo_requests.reject') }}" onsubmit="return confirm('Reject promo request?');">
                    @csrf
                    <input type="hidden" name="id" value="{{ $r->id }}">
                    <button type="submit" class="btn btn-action btn-reject border-0 text-danger"><i class="bi bi-x fs-5"></i></button>
                </form>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $r->vendor_phone) }}" target="_blank" class="btn btn-dark"><i class="bi bi-whatsapp"></i></a>
            </div>
        </div>
        @endforeach
        
        @if($requests->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $requests->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .product-preview { width: 48px; height: 48px; border-radius: 12px; object-fit: cover; }
    .stat-badge { background: rgba(99, 102, 241, 0.1); color: var(--bs-primary); font-size: 0.65rem; padding: 4px 10px; border-radius: 50px; font-weight: 800; }
    @media (max-width: 768px) {
        .table-responsive { display: none; }
    }
    @media (min-width: 769px) { .mobile-list { display: none; } }
</style>
@endsection
