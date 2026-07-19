@extends('layouts.vendor')

@section('title', 'Stock Management')

@section('styles')
<style>
    .constructive-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }
    @media (max-width: 992px) {
        .constructive-row {
            grid-template-columns: 1fr;
        }
    }
    
    .po-card-metric {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #eef0f3;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .metric-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .metric-title-text {
        font-size: 0.82rem;
        color: #7d8da1;
        font-weight: 700;
    }
    
    .metric-gauge-box {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin: 15px 0;
        height: 100px;
    }
    .circular-gauge {
        width: 100px;
        height: 100px;
        transform: rotate(-90deg);
    }
    .circular-gauge circle {
        fill: none;
        stroke-width: 3.5;
    }
    .circular-gauge .bg-ring {
        stroke: #f1f3f9;
    }
    .circular-gauge .fill-ring {
        stroke-linecap: round;
        transition: stroke-dasharray 0.8s ease;
    }
    .gauge-percentage {
        position: absolute;
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-dark);
    }
    
    .metric-foot {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 10px;
    }
    .metric-val-num {
        font-size: 1.8rem;
        font-weight: 900;
        color: var(--text-dark);
        line-height: 1;
    }

    .chart-panel-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #eef0f3;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
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

    .status-pill { 
        padding: 5px 12px; border-radius: 50px; font-weight: 800; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; 
    }
    .bg-low { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .bg-out { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }
    .bg-ok { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }

    .stock-input { 
        max-width: 90px; border-radius: 8px; font-weight: 700; border: 1px solid #e2e8f0; text-align: center; padding: 0.35rem; 
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4 p-lg-5">
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark"><i class="bi bi-sliders text-primary me-2"></i>Stock Management</h2>
            <p class="text-muted m-0 small">Monitor stock metrics and optimize item inventory status</p>
        </div>
    </div>

    @if (session('msg'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('msg') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger shadow-sm rounded-3 mb-4"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <!-- KPI Metrics Row -->
    <div class="constructive-row">
        <!-- Metric 1: Total Listed -->
        <div class="po-card-metric">
            <div class="metric-head">
                <span class="metric-title-text">Total Items</span>
                <span class="text-muted"><i class="bi bi-three-dots"></i></span>
            </div>
            <div class="metric-gauge-box">
                <svg viewBox="0 0 36 36" class="circular-gauge">
                    <circle class="bg-ring" cx="18" cy="18" r="15.915"></circle>
                    <circle class="fill-ring" cx="18" cy="18" r="15.915" stroke="#4F46E5" stroke-dasharray="100 100"></circle>
                </svg>
                <div class="gauge-percentage">{{ count($products) }}</div>
            </div>
            <div class="metric-foot">
                <div>
                    <span class="text-muted d-block small fw-bold">TOTAL PORTFOLIO</span>
                </div>
            </div>
        </div>

        <!-- Metric 2: Low Stock alerts -->
        <div class="po-card-metric">
            <div class="metric-head">
                <span class="metric-title-text">Low Stock Alerts</span>
                <span class="text-muted"><i class="bi bi-three-dots"></i></span>
            </div>
            <div class="metric-gauge-box">
                <svg viewBox="0 0 36 36" class="circular-gauge">
                    <circle class="bg-ring" cx="18" cy="18" r="15.915"></circle>
                    <circle class="fill-ring" cx="18" cy="18" r="15.915" stroke="#f59e0b" stroke-dasharray="{{ (count($products) > 0) ? round(($lowStockCount / count($products)) * 100) : 0 }} 100"></circle>
                </svg>
                <div class="gauge-percentage text-warning">{{ $lowStockCount }}</div>
            </div>
            <div class="metric-foot">
                <div>
                    <span class="text-muted d-block small fw-bold">REORDER SOON</span>
                </div>
            </div>
        </div>

        <!-- Metric 3: Out of Stock -->
        <div class="po-card-metric">
            <div class="metric-head">
                <span class="metric-title-text">Out of Stock</span>
                <span class="text-muted"><i class="bi bi-three-dots"></i></span>
            </div>
            <div class="metric-gauge-box">
                <svg viewBox="0 0 36 36" class="circular-gauge">
                    <circle class="bg-ring" cx="18" cy="18" r="15.915"></circle>
                    <circle class="fill-ring" cx="18" cy="18" r="15.915" stroke="#ef4444" stroke-dasharray="{{ (count($products) > 0) ? round(($outOfStockCount / count($products)) * 100) : 0 }} 100"></circle>
                </svg>
                <div class="gauge-percentage text-danger">{{ $outOfStockCount }}</div>
            </div>
            <div class="metric-foot">
                <div>
                    <span class="text-muted d-block small fw-bold">CRITICAL ACTION REQUIRED</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory table -->
    <div class="chart-panel-card p-0 overflow-hidden mb-5">
        <div class="p-4 border-bottom bg-white">
            <span class="chart-panel-title">Stock Ledger Sync</span>
        </div>
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Product Descriptor</th>
                        <th class="text-center">Current Inventory</th>
                        <th class="text-center">Marketplace Status</th>
                        <th class="text-end pe-4">Manual Adjustment</th>
                    </tr>
                </thead>
                <tbody>
                    @if($products->isEmpty())
                        <tr><td colspan="4" class="text-center py-5 text-muted">Awaiting product deployment...</td></tr>
                    @else
                        @foreach($products as $p)
                            @php
                                $stock = $p->stock_quantity;
                                $pillClass = 'bg-ok';
                                $pillText = 'Optimal';
                                if ($stock <= 0) {
                                    $pillClass = 'bg-out';
                                    $pillText = 'Out of Stock';
                                } elseif ($stock <= 5) {
                                    $pillClass = 'bg-low';
                                    $pillText = 'Low Level';
                                }
                                
                                $img = $p->image_url;
                                $imgSrc = $img ? asset('assets/uploads/products/' . $img) : 'https://placehold.co/100?text=No+Media';
                                if ($img && str_starts_with($img, 'http')) {
                                    $imgSrc = $img;
                                }
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $imgSrc }}" class="prod-thumb" onerror="this.src='https://placehold.co/100?text=No+Media'">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $p->title }}</div>
                                            <small class="text-muted text-uppercase" style="font-size: 10px;">{{ $p->category }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold text-dark">{{ $stock }}</td>
                                <td class="text-center">
                                    <span class="status-pill {{ $pillClass }}">{{ $pillText }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <form method="POST" action="{{ route('vendor.inventory') }}" class="d-flex align-items-center justify-content-end gap-2 m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="update_stock" value="1">
                                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                                        <input type="number" name="stock_qty" class="stock-input" value="{{ $stock }}" min="0">
                                        <button type="submit" class="btn btn-sm btn-primary fw-bold" style="border-radius:8px; border:none; background:var(--primary); padding: 0.4rem 1rem;">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
