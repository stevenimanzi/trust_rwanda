@extends('layouts.vendor')

@section('title', 'Sales Analytics')

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
        height: 100%;
    }
    .chart-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
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
    
    .product-img-circle {
        width: 38px; height: 38px; border-radius: 10px;
        background: var(--primary-light); color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-weight: 800;
    }

    .smooth-chart-svg {
        width: 100%;
        height: 280px;
    }
    .chart-line-path {
        stroke: #4F46E5;
        stroke-width: 3.5;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .chart-area-path {
        fill: url(#chartGradient2);
    }
    .chart-grid-h {
        stroke: #f1f3f7;
        stroke-width: 1;
        stroke-dasharray: 4 4;
    }
    .chart-dot-active {
        fill: #4F46E5;
        stroke: white;
        stroke-width: 3.5;
        filter: drop-shadow(0 4px 10px rgba(79,70,229,0.5));
    }
    .chart-tooltip-bubble {
        fill: #1e1b4b;
        filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));
    }
    .chart-tooltip-text {
        fill: white;
        font-size: 10px;
        font-weight: 800;
        font-family: sans-serif;
    }

    .svg-doughnut-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        height: 240px;
    }
    .doughnut-svg-container {
        width: 160px;
        height: 160px;
        transform: rotate(-90deg);
    }
    .doughnut-value-center {
        position: absolute;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        line-height: 1.1;
        text-align: center;
    }
    .doughnut-value-num {
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--text-dark);
    }
    .doughnut-value-lbl {
        font-size: 0.6rem;
        color: #94a3b8;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .legend-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px 12px;
        margin-top: 15px;
        width: 100%;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .legend-color-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4 p-lg-5">
    
    <!-- Top Row KPIs -->
    <div class="constructive-row">
        <!-- Metric 1: Total Sales Earnings -->
        <div class="po-card-metric">
            <div class="metric-head">
                <span class="metric-title-text">Total Sales Earnings</span>
                <span class="text-muted"><i class="bi bi-three-dots"></i></span>
            </div>
            <div class="metric-gauge-box">
                <svg viewBox="0 0 36 36" class="circular-gauge">
                    <circle class="bg-ring" cx="18" cy="18" r="15.915"></circle>
                    <circle class="fill-ring" cx="18" cy="18" r="15.915" stroke="#4F46E5" stroke-dasharray="100 100"></circle>
                </svg>
                <div class="gauge-percentage"><i class="bi bi-wallet2 text-primary fs-3"></i></div>
            </div>
            <div class="metric-foot">
                <div>
                    <span class="text-muted d-block small fw-bold">TOTAL EARNED</span>
                    <span class="metric-val-num" style="font-size:1.35rem;">{{ number_format($totalSales) }} <span class="fs-6 opacity-75 fw-normal">RWF</span></span>
                </div>
            </div>
        </div>

        <!-- Metric 2: Successful Orders -->
        <div class="po-card-metric">
            <div class="metric-head">
                <span class="metric-title-text">Successful Orders</span>
                <span class="text-muted"><i class="bi bi-three-dots"></i></span>
            </div>
            <div class="metric-gauge-box">
                <svg viewBox="0 0 36 36" class="circular-gauge">
                    <circle class="bg-ring" cx="18" cy="18" r="15.915"></circle>
                    <circle class="fill-ring" cx="18" cy="18" r="15.915" stroke="#f59e0b" stroke-dasharray="100 100"></circle>
                </svg>
                <div class="gauge-percentage"><i class="bi bi-cart-check text-warning fs-3"></i></div>
            </div>
            <div class="metric-foot">
                <div>
                    <span class="text-muted d-block small fw-bold">ORDERS COUNT</span>
                    <span class="metric-val-num">{{ $orderCount }}</span>
                </div>
            </div>
        </div>

        <!-- Metric 3: Live Catalog -->
        <div class="po-card-metric">
            <div class="metric-head">
                <span class="metric-title-text">Live Catalog</span>
                <span class="text-muted"><i class="bi bi-three-dots"></i></span>
            </div>
            <div class="metric-gauge-box">
                <svg viewBox="0 0 36 36" class="circular-gauge">
                    <circle class="bg-ring" cx="18" cy="18" r="15.915"></circle>
                    <circle class="fill-ring" cx="18" cy="18" r="15.915" stroke="#10b981" stroke-dasharray="100 100"></circle>
                </svg>
                <div class="gauge-percentage"><i class="bi bi-box-seam text-success fs-3"></i></div>
            </div>
            <div class="metric-foot">
                <div>
                    <span class="text-muted d-block small fw-bold">PRODUCTS</span>
                    <span class="metric-val-num">{{ $productCount }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-5">
        <div class="col-xl-8">
            <div class="chart-panel-card">
                <div class="chart-panel-header">
                    <span class="chart-panel-title">Revenue Growth Engine</span>
                </div>
                <div class="position-relative">
                    <!-- Dynamic SVG chart representing valuation trend curve -->
                    <svg class="smooth-chart-svg" viewBox="0 0 760 280">
                        <defs>
                            <linearGradient id="chartGradient2" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#4F46E5" stop-opacity="0.22" />
                                <stop offset="100%" stop-color="#4F46E5" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        <!-- Grid horizontal lines -->
                        <line class="chart-grid-h" x1="40" x2="740" y1="50" y2="50" />
                        <line class="chart-grid-h" x1="40" x2="740" y1="100" y2="100" />
                        <line class="chart-grid-h" x1="40" x2="740" y1="150" y2="150" />
                        <line class="chart-grid-h" x1="40" x2="740" y1="200" y2="200" />
                        <line class="chart-grid-h" x1="40" x2="740" y1="250" y2="250" />

                        <!-- Left Labels -->
                        <text x="15" y="54" fill="#a3acba" font-size="10" font-weight="700">Max</text>
                        <text x="15" y="154" fill="#a3acba" font-size="10" font-weight="700">Mid</text>
                        <text x="15" y="254" fill="#a3acba" font-size="10" font-weight="700">0</text>

                        <!-- Gradient area and path line -->
                        <path class="chart-area-path" d="{{ $areaD }}" />
                        <path class="chart-line-path" d="{{ $pathD }}" />

                        <!-- Hover vertical dotted marker line -->
                        <line x1="{{ $points[6]['x'] }}" x2="{{ $points[6]['x'] }}" y1="{{ $points[6]['y'] }}" y2="250" stroke="#4F46E5" stroke-width="1.5" stroke-dasharray="3 3" />

                        <!-- Glow Dot indicator -->
                        <circle class="chart-dot-active" cx="{{ $points[6]['x'] }}" cy="{{ $points[6]['y'] }}" r="6" />

                        <!-- Tooltip bubble matching Constructive layout -->
                        <rect class="chart-tooltip-bubble" x="{{ $points[6]['x'] - 55 }}" y="{{ $points[6]['y'] - 42 }}" width="110" height="30" rx="6" />
                        <polygon points="{{ $points[6]['x'] }},{{ $points[6]['y'] - 7 }} {{ $points[6]['x'] - 5 }},{{ $points[6]['y'] - 12 }} {{ $points[6]['x'] + 5 }},{{ $points[6]['y'] - 12 }}" fill="#1e1b4b" />
                        <text class="chart-tooltip-text" x="{{ $points[6]['x'] }}" y="{{ $points[6]['y'] - 24 }}" text-anchor="middle">{{ number_format($points[6]['val']) }} RWF</text>

                        <!-- Bottom X Labels -->
                        <text x="60" y="274" fill="#a3acba" font-size="9" font-weight="700" text-anchor="middle">{{ $dates[0] }}</text>
                        <text x="170" y="274" fill="#a3acba" font-size="9" font-weight="700" text-anchor="middle">{{ $dates[1] }}</text>
                        <text x="280" y="274" fill="#a3acba" font-size="9" font-weight="700" text-anchor="middle">{{ $dates[2] }}</text>
                        <text x="390" y="274" fill="#a3acba" font-size="9" font-weight="700" text-anchor="middle">{{ $dates[3] }}</text>
                        <text x="500" y="274" fill="#a3acba" font-size="9" font-weight="700" text-anchor="middle">{{ $dates[4] }}</text>
                        <text x="610" y="274" fill="#a3acba" font-size="9" font-weight="700" text-anchor="middle">{{ $dates[5] }}</text>
                        <text x="720" y="274" fill="#a3acba" font-size="9" font-weight="700" text-anchor="middle">{{ $dates[6] }}</text>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="chart-panel-card">
                <div class="chart-panel-header">
                    <span class="chart-panel-title">Unit Velocity (Top Products)</span>
                </div>
                
                <div class="svg-doughnut-wrapper">
                    <!-- Mathematical custom SVG doughnut graph segment stack -->
                    <div class="doughnut-svg-container">
                        <svg viewBox="0 0 36 36" style="width: 100%; height: 100%;">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#f1f3f9" stroke-width="4.2"></circle>
                            @if ($totalSalesCount > 0)
                                @foreach ($doughnutSegments as $seg)
                                    <circle cx="18" cy="18" r="15.915" fill="none" 
                                            stroke="{{ $seg['color'] }}" 
                                            stroke-width="4.2" 
                                            stroke-dasharray="{{ $seg['dasharray'] }}" 
                                            stroke-dashoffset="{{ $seg['dashoffset'] }}" 
                                            stroke-linecap="round"></circle>
                                @endforeach
                            @endif
                        </svg>
                    </div>
                    <div class="doughnut-value-center">
                        <span class="doughnut-value-num">{{ $totalSalesCount }}</span>
                        <span class="doughnut-value-lbl">Sales</span>
                    </div>
                </div>

                <div class="legend-grid">
                    @foreach ($doughnutSegments as $seg)
                        <div class="legend-item" title="{{ $seg['title'] }}">
                            <span class="legend-color-dot" style="background: {{ $seg['color'] }}"></span>
                            <span class="text-truncate">{{ $seg['title'] }} ({{ $seg['percent'] }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products Grid -->
    <div class="chart-panel-card p-0 overflow-hidden mb-5">
        <div class="p-4 border-bottom bg-white">
            <span class="chart-panel-title"><i class="bi bi-trophy text-primary me-2"></i>Top Performing Inventory</span>
        </div>
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Product Details</th>
                        <th class="text-center">Units Sold</th>
                        <th class="text-end pe-4">Gross Income</th>
                    </tr>
                </thead>
                <tbody>
                    @if($topProducts->isEmpty())
                        <tr><td colspan="3" class="text-center py-5 text-muted">Awaiting initial sales data...</td></tr>
                    @else
                        @foreach($topProducts as $item)
                            @php
                                $pName = $item->title ?? 'Archived Product';
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="product-img-circle">
                                            {{ strtoupper(substr($pName, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $pName }}</div>
                                            <div class="small text-muted">SKU: KP-{{ $item->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold text-muted">{{ $item->sales_count }}</td>
                                <td class="text-end pe-4 fw-bold text-primary">{{ number_format($item->revenue) }} RWF</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
