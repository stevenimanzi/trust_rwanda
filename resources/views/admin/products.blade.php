@extends('layouts.admin')

@section('title', 'Master Inventory')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-800 m-0 text-dark">INVENTORY_NODE</h4>
    <button class="btn btn-primary rounded-pill px-4 py-2 fw-900 small shadow-lg no-print" onclick="window.print()">
        <i class="bi bi-printer-fill me-1"></i> <span>EXPORT</span>
    </button>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div><div class="hz-card-subtitle">Total Inventory</div></div>
                <div class="hz-icon-btn shadow-sm" style="background: var(--hz-primary-light); color: var(--hz-primary);"><i class="bi bi-box-seam"></i></div>
            </div>
            <div class="hz-kpi-value mb-2">{{ $totalProducts }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div><div class="hz-card-subtitle">Live Listings</div></div>
                <div class="hz-icon-btn shadow-sm" style="background: #dcfce7; color: #166534;"><i class="bi bi-broadcast"></i></div>
            </div>
            <div class="hz-kpi-value mb-2">{{ $activeProducts }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div><div class="hz-card-subtitle">Low Stock</div></div>
                <div class="hz-icon-btn shadow-sm" style="background: #fef3c7; color: #92400e;"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
            <div class="hz-kpi-value mb-2">{{ $lowStock }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div><div class="hz-card-subtitle">Categories</div></div>
                <div class="hz-icon-btn shadow-sm" style="background: #e0f2fe; color: #0284c7;"><i class="bi bi-tags"></i></div>
            </div>
            <div class="hz-kpi-value mb-2">{{ $categories->count() }}</div>
        </div>
    </div>
</div>

<div class="mb-4">
    <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 align-items-center">
        <div class="col-lg-8">
            <div class="input-group shadow-sm" style="border-radius: 50px; overflow: hidden;">
                <span class="input-group-text bg-white border-0 text-muted ps-4"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control border-0 py-3" placeholder="Search Catalog Matrix..." value="{{ $search }}" style="box-shadow: none;">
                <button type="submit" class="btn btn-primary px-4 fw-bold">Search</button>
            </div>
        </div>
        <div class="col-lg-4">
            <select name="category" class="hz-form-control w-100 fw-bold" onchange="this.form.submit()">
                <option value="all">ALL CATEGORIES</option>
                @foreach($categories as $c)
                    <option value="{{ $c->name }}" {{ $catFilter == $c->name ? 'selected' : '' }}>{{ strtoupper($c->name) }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="hz-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="hz-card-title"><i class="bi bi-list-ul"></i> Product Catalog</div>
    </div>
    
    <div class="table-responsive">
        <table class="table hz-table">
            <thead>
                <tr>
                    <th>Product Specifications</th>
                    <th>Merchant</th>
                    <th>Stock/Price</th>
                    <th>Status</th>
                    <th class="text-end no-print">Command</th>
                </tr>
            </thead>
            <tbody>
                @if($products->isEmpty())
                    <tr><td colspan="5" class="text-center py-5 opacity-50 small fw-bold">NULL_DATA_RECORDS</td></tr>
                @else
                    @foreach($products as $p)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ kura_product_image_url($p->image_url, 'https://placehold.co/100?text=KURA') }}" style="width: 45px; height: 45px; border-radius: 12px; object-fit: cover; border: 1px solid var(--hz-border);" onerror="this.src='https://placehold.co/100?text=KURA'">
                                <div>
                                    <div class="fw-bold text-dark">{{ $p->title }}</div>
                                    <div class="small text-muted">{{ strtoupper($p->category) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-bold text-dark"><i class="bi bi-shop me-1"></i>{{ $p->shop_name }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ number_format($p->price) }} RWF</div>
                            <div class="small fw-bold {{ $p->stock_quantity < 5 ? 'text-danger' : 'text-success' }}" style="font-size:0.65rem;">VOL: {{ $p->stock_quantity }}</div>
                        </td>
                        <td>
                            @if($p->is_visible)
                                <span class="hz-badge hz-badge-success">ACTIVE</span>
                            @else
                                <span class="hz-badge hz-badge-warning">OFFLINE</span>
                            @endif
                        </td>
                        <td class="text-end no-print">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;">
                                    <li><a class="dropdown-item py-2" href="#" target="_blank"><i class="bi bi-eye me-2 text-primary"></i> Preview Site</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.products.toggle') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $p->id }}"><input type="hidden" name="new_status" value="{{ $p->is_visible ? '0' : '1' }}">
                                            <button type="submit" class="dropdown-item py-2 {{ $p->is_visible ? 'text-warning' : 'text-success' }}">
                                                <i class="bi bi-power me-2"></i> {{ $p->is_visible ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.products.delete') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                                            <button type="submit" class="dropdown-item py-2 text-danger" onclick="return confirm('Purge node?')"><i class="bi bi-trash me-2"></i> Delete SKU</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="mobile-list">
        @foreach ($products as $p)
        <div class="mobile-product-card">
            <div class="d-flex gap-3 mb-3">
                <img src="{{ kura_product_image_url($p->image_url, 'https://placehold.co/100?text=KURA') }}" class="product-thumb" onerror="this.src='https://placehold.co/100?text=KURA'">
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-900 small text-truncate">{{ $p->title }}</div>
                    <div class="text-primary fw-bold" style="font-size:0.7rem;">{{ $p->shop_name }}</div>
                    <div class="fw-900 mt-1" style="font-size:0.85rem;">{{ number_format($p->price) }} RWF</div>
                </div>
                <span class="status-badge {{ $p->is_visible ? 'st-live' : 'st-hidden' }} h-100">{{ $p->is_visible ? 'LIVE' : 'OFF' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-3 border-top border-dark border-opacity-10">
                <div class="small fw-bold {{ $p->stock_quantity < 5 ? 'text-danger' : 'text-muted' }}">STOCK: {{ $p->stock_quantity }}</div>
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('admin.products.toggle') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}"><input type="hidden" name="new_status" value="{{ $p->is_visible ? '0' : '1' }}">
                        <button type="submit" class="btn-action border-0 {{ $p->is_visible ? 'text-warning' : 'text-success' }}"><i class="bi bi-power"></i></button>
                    </form>
                    <form method="POST" action="{{ route('admin.products.delete') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <button type="submit" class="btn-action text-danger border-0" onclick="return confirm('Purge SKU?')"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
<style>
    .bg-grad-info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; }

    .card-pro { background: var(--admin-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.75rem; box-shadow: var(--shadow-sm); transition: all 0.3s ease; }
    .card-pro:hover { box-shadow: var(--shadow-md); border-color: #cbd5e1; }
    
    .card-header-title { font-weight: 800; color: var(--admin-text); font-size: 1.1rem; display: flex; align-items: center; gap: 0.6rem; }
    .card-header-title i { color: var(--admin-accent); background: var(--admin-accent-light); padding: 0.4rem 0.5rem; border-radius: 10px; font-size: 1.1rem; }

    .table-custom { color: var(--admin-text); vertical-align: middle; border-collapse: separate; border-spacing: 0 0.5rem; white-space: nowrap; }
    .table-custom thead th { border: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--admin-muted); padding: 1rem; font-weight: 800; }
    .table-custom tbody tr { background-color: #f8fafc; transition: all 0.2s ease; border-radius: 16px; }
    .table-custom tbody tr:hover { background-color: white; box-shadow: var(--shadow-md); }
    .table-custom td { padding: 1rem; border: none; border-top: 1px solid transparent; border-bottom: 1px solid transparent; }
    .table-custom td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; border-left: 1px solid transparent; }
    .table-custom td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; border-right: 1px solid transparent; }
    .table-custom tbody tr:hover td { border-color: var(--border); }
    
    .product-thumb { width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border); background: #f1f5f9; }
    .status-badge { font-size: 0.6rem; font-weight: 800; padding: 5px 12px; border-radius: 50px; text-transform: uppercase; }
    .st-live { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid #10b981; }
    .st-hidden { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid #ef4444; }

    .table-responsive { overflow: visible !important; }
    
    .mobile-list { display: none; }
    @media (max-width: 768px) {
        .table-responsive { display: none; }
        .mobile-list { display: block; }
        .mobile-product-card { background: white; border: 1px solid var(--hz-border); border-radius: 16px; padding: 1rem; margin-bottom: 1rem; box-shadow: var(--hz-shadow-sm); }
        .mobile-product-card .img-thumb { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; }
    }
</style>
@endsection
