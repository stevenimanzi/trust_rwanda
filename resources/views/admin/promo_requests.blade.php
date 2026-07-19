@extends('layouts.admin')

@section('title', 'Promo Requests')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-800 m-0">PROMO_QUEUE</h4>
        <p class="text-muted small m-0">Validate and deploy vendor ad campaigns</p>
    </div>
    <span class="badge bg-indigo-subtle text-primary p-2 px-3 rounded-pill fw-800 border border-primary border-opacity-25">
        {{ $requests->count() }} PENDING
    </span>
</div>

<div class="card-pro">
    <div class="table-responsive desktop-table">
        <table class="table align-middle">
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
                                    <button type="submit" class="btn btn-action btn-approve border-0"><i class="bi bi-check-circle-fill me-1"></i> Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.promo_requests.reject') }}" class="d-inline" onsubmit="return confirm('Reject promo request?');">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $r->id }}">
                                    <button type="submit" class="btn btn-action btn-reject border-0">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
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
                    <button type="submit" class="btn btn-action btn-reject border-0"><i class="bi bi-x"></i></button>
                </form>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $r->vendor_phone) }}" target="_blank" class="btn btn-action bg-dark text-white border-secondary"><i class="bi bi-whatsapp"></i></a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-pro { background: var(--adm-card); border-radius: 28px; border: 1px solid var(--border); padding: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    .table { color: var(--adm-text); vertical-align: middle; }
    .table thead th { color: #64748b; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 1px solid var(--border); padding: 1rem; }
    .table td { border-bottom: 1px solid var(--border); padding: 1rem; }

    .product-preview { width: 48px; height: 48px; border-radius: 12px; object-fit: cover; background: #f1f5f9; border: 1px solid var(--border); }
    .btn-action { border-radius: 12px; font-weight: 800; font-size: 0.75rem; padding: 0.5rem 1rem; transition: 0.3s; display: inline-flex; align-items: center; justify-content: center; }
    .btn-approve { background: var(--adm-accent); border: none; color: white; }
    .btn-reject { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; }
    
    .stat-badge { background: rgba(99, 102, 241, 0.1); color: var(--adm-accent); font-size: 0.65rem; padding: 4px 10px; border-radius: 50px; font-weight: 800; }

    @media (max-width: 768px) {
        .desktop-table { display: none; }
        .mobile-request-card { background: #ffffff; border: 1px solid var(--border); border-radius: 20px; padding: 1.25rem; margin-bottom: 1rem; }
    }
    @media (min-width: 769px) { .mobile-list { display: none; } }
</style>
@endsection
