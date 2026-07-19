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
        <div class="kpi-card bg-grad-primary">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-val">{{ $totalProducts }}</div>
                    <div class="kpi-label">Total Inventory</div>
                </div>
                <div class="kpi-icon"><i class="bi bi-box-seam"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card bg-grad-success">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-val">{{ $activeProducts }}</div>
                    <div class="kpi-label">Live Listings</div>
                </div>
                <div class="kpi-icon"><i class="bi bi-broadcast"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card bg-grad-warning">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-val">{{ $lowStock }}</div>
                    <div class="kpi-label">Low Stock</div>
                </div>
                <div class="kpi-icon"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card bg-grad-info">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-val">{{ $categories->count() }}</div>
                    <div class="kpi-label">Categories</div>
                </div>
                <div class="kpi-icon"><i class="bi bi-tags"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="search-container no-print">
    <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 align-items-center">
        <div class="col-lg-8">
            <div class="input-group">
                <span class="input-group-text border-0 rounded-start-pill ps-3 text-primary" style="background: #f1f5f9;"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control border-0 rounded-end-pill ps-2" placeholder="Search Catalog Matrix..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-lg-4">
            <select name="category" class="form-select border-0 rounded-pill fw-bold" onchange="this.form.submit()">
                <option value="all">ALL CATEGORIES</option>
                @foreach($categories as $c)
                    <option value="{{ $c->category }}" {{ $catFilter == $c->category ? 'selected' : '' }}>{{ strtoupper($c->category) }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="card-pro">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="card-header-title"><i class="bi bi-list-ul"></i> Product Catalog</div>
    </div>
    
    <div class="table-responsive desktop-table">
        <table class="table table-custom">
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
                                <img src="{{ kura_product_image_url($p->image_url, 'https://placehold.co/100?text=KURA') }}" class="product-thumb" onerror="this.src='https://placehold.co/100?text=KURA'">
                                <div>
                                    <div class="fw-800 small">{{ $p->title }}</div>
                                    <div class="badge bg-indigo-subtle text-primary fw-900" style="font-size:0.6rem;">{{ strtoupper($p->category) }}</div>
                                </div>
                            </div>
                        </td>
                        <td><div class="fw-800 small text-info"><i class="bi bi-shop me-1"></i>{{ $p->shop_name }}</div></td>
                        <td>
                            <div class="fw-900 small">{{ number_format($p->price) }} RWF</div>
                            <div class="small fw-bold {{ $p->stock_quantity < 5 ? 'text-danger' : 'text-success' }}" style="font-size:0.65rem;">VOL: {{ $p->stock_quantity }}</div>
                        </td>
                        <td><span class="status-badge {{ $p->is_visible ? 'st-live' : 'st-hidden' }}">{{ $p->is_visible ? 'ACTIVE' : 'OFFLINE' }}</span></td>
                        <td class="text-end no-print">
                            <div class="dropdown">
                                <button class="btn-action border-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end border shadow-lg rounded-4 p-2">
                                    <li><a class="dropdown-item rounded-3 py-2" href="#" target="_blank"><i class="bi bi-eye me-2"></i> Preview Site</a></li>
                                    <li><hr class="dropdown-divider border-dark border-opacity-10"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.products.toggle') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $p->id }}"><input type="hidden" name="new_status" value="{{ $p->is_visible ? '0' : '1' }}">
                                            <button type="submit" class="dropdown-item rounded-3 py-2 {{ $p->is_visible ? 'text-warning' : 'text-success' }}"><i class="bi bi-power me-2"></i> {{ $p->is_visible ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.products.delete') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                                            <button type="submit" class="dropdown-item rounded-3 py-2 text-danger" onclick="return confirm('Purge node?')"><i class="bi bi-trash me-2"></i> Delete SKU</button>
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
    .search-container { background: var(--admin-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-sm); }
    .form-control, .form-select { background: #f1f5f9; color: var(--admin-text); border: 1px solid var(--border); border-radius: 12px; padding: 0.75rem; font-weight: 600; }
    .form-control:focus, .form-select:focus { background: #ffffff; border-color: var(--admin-accent); color: var(--admin-text); box-shadow: none; }

    .kpi-card { border-radius: 24px; padding: 1.5rem; border: none; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: var(--shadow-sm); position: relative; overflow: hidden; z-index: 1; height: 100%; }
    .kpi-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .kpi-card::before { content: ''; position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.15); z-index: -1; transition: all 0.4s ease; }
    .kpi-card:hover::before { transform: scale(1.2); }
    
    .kpi-icon { width: 56px; height: 56px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; background: rgba(255, 255, 255, 0.25); color: white; margin-bottom: 1rem; box-shadow: var(--shadow-sm); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.3); }
    .kpi-val { font-size: 2rem; font-weight: 900; letter-spacing: -0.5px; line-height: 1.1; margin-bottom: 0.25rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1); color: white; }
    .kpi-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; color: white; }

    .bg-grad-primary { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color: white; }
    .bg-grad-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .bg-grad-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
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

    .btn-action { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: var(--admin-text); border: 1px solid var(--border); transition: 0.3s; }
    .btn-action:hover { background: var(--admin-accent); color: white; border-color: var(--admin-accent); }

    .dropdown-menu { background: #ffffff; border: 1px solid var(--border); border-radius: 18px; padding: 10px; box-shadow: var(--shadow-lg); }
    .dropdown-item { color: var(--admin-text); border-radius: 10px; font-size: 0.85rem; padding: 10px; }
    .dropdown-item:hover { background: var(--admin-accent); color: white; }

    @media (max-width: 768px) {
        .desktop-table { display: none; }
        .mobile-product-card { background: #ffffff; border: 1px solid var(--border); border-radius: 20px; padding: 1.25rem; margin-bottom: 1rem; }
    }
    @media (min-width: 769px) { .mobile-list { display: none; } }
</style>
@endsection
