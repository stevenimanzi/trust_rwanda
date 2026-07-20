@extends('layouts.admin')

@section('title', 'Sales Intelligence')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 no-print">
    <div>
        <h4 class="fw-800 m-0 text-dark">SALES_INTELLIGENCE</h4>
        <p class="text-muted small m-0 mt-1">Platform analytics and transactions breakdown</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports', ['range' => '7']) }}" class="filter-pill {{ $range=='7'?'active':'' }}">7D</a>
        <a href="{{ route('admin.reports', ['range' => '30']) }}" class="filter-pill {{ $range=='30'?'active':'' }}">30D</a>
        <a href="{{ route('admin.reports', ['range' => '90']) }}" class="filter-pill {{ $range=='90'?'active':'' }}">90D</a>
        <a href="{{ route('admin.reports', ['range' => 'all']) }}" class="filter-pill {{ $range=='all'?'active':'' }}">ALL</a>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="hz-card p-4 d-flex align-items-center gap-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width:60px; height:60px; font-size: 1.5rem; background: var(--hz-primary-light); color: var(--hz-primary);">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Gross Volume</div>
                <div class="fs-4 fw-900 text-dark">{{ number_format($totalRevenue) }} <span class="fs-6 text-muted">RWF</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="hz-card p-4 d-flex align-items-center gap-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width:60px; height:60px; font-size: 1.5rem; background: var(--hz-secondary-light); color: var(--hz-secondary);">
                <i class="bi bi-cart-check"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Fulfillments</div>
                <div class="fs-4 fw-900 text-dark">{{ number_format($totalOrders) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="hz-card p-4 d-flex align-items-center gap-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width:60px; height:60px; font-size: 1.5rem; background: var(--hz-tertiary-light); color: var(--hz-tertiary);">
                <i class="bi bi-calculator"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Avg Order Value</div>
                <div class="fs-4 fw-900 text-dark">{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders) : 0 }} <span class="fs-6 text-muted">RWF</span></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="hz-card p-4">
            <h5 class="fw-800 text-dark mb-4"><i class="bi bi-graph-up-arrow text-muted me-2"></i> FULFILLMENT TREND</h5>
            <div style="height: 300px;"><canvas id="revenueTrendChart"></canvas></div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="hz-card p-4">
            <h5 class="fw-800 text-dark mb-4"><i class="bi bi-pie-chart text-muted me-2"></i> CATEGORY MIX</h5>
            <div style="height: 300px;"><canvas id="categoryBreakdownChart"></canvas></div>
        </div>
    </div>
</div>

<div class="hz-card p-4 mb-4">
    <h5 class="fw-800 text-dark mb-4 border-bottom pb-3"><i class="bi bi-shop text-muted me-2"></i> TOP MERCHANT DISPATCH</h5>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Merchant Brand</th>
                    <th>Fulfillments</th>
                    <th>Market Share</th>
                    <th class="text-end">Volume Revenue</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($topVendors))
                    <tr><td colspan="4" class="text-center py-4 opacity-50 fw-bold">NO SALES DATA CURRENTLY IN RANGE</td></tr>
                @else
                    @foreach ($topVendors as $row)
                    @php
                        $share = $totalRevenue > 0 ? ($row->total_sales / $totalRevenue) * 100 : 0;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width:40px; height:40px; font-size: 1rem; background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                    {{ strtoupper(substr($row->shop_name, 0, 1)) }}
                                </div>
                                <div class="fw-900 text-dark">{{ $row->shop_name }}</div>
                            </div>
                        </td>
                        <td><span class="badge bg-indigo-subtle text-primary border border-primary border-opacity-10 px-3 py-1 rounded-pill fw-bold">{{ $row->order_count }} Fulfilled</span></td>
                        <td style="width: 25%;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $share }}%" aria-valuenow="{{ $share }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="small fw-bold text-muted">{{ number_format($share, 1) }}%</div>
                            </div>
                        </td>
                        <td class="text-end fw-900 text-dark">{{ number_format($row->total_sales) }} RWF</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const trendCtx = document.getElementById('revenueTrendChart');
        if (trendCtx) {
            let gradient = trendCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'GTV Sales (RWF)',
                        data: {!! json_encode($chartRevenue) !!},
                        borderColor: '#ff6a3e', // hz-primary
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#ff6a3e',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#64748b' } },
                        x: { grid: { display: false }, ticks: { color: '#64748b' } }
                    }
                }
            });
        }

        const catCtx = document.getElementById('categoryBreakdownChart');
        if (catCtx) {
            new Chart(catCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($catLabels) !!},
                    datasets: [{
                        data: {!! json_encode($catCounts) !!},
                        backgroundColor: ['#ff6a3e', '#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6'], // Horizon theme palette
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } } }
                }
            });
        }
    })();
</script>
@endsection

@section('styles')
<style>
    .filter-pill { border: 1px solid var(--border); background: #f1f5f9; color: var(--admin-muted); font-weight: 800; padding: 0.6rem 1.5rem; border-radius: 50px; transition: 0.3s; text-decoration: none; font-size: 0.8rem; }
    .filter-pill:hover, .filter-pill.active { background: var(--admin-accent); color: white; border-color: var(--admin-accent); box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3); }

    .table-custom { color: var(--admin-text); vertical-align: middle; border-collapse: separate; border-spacing: 0 0.5rem; white-space: nowrap; }
    .table-custom thead th { border: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--admin-muted); padding: 1rem; font-weight: 800; }
    .table-custom tbody tr { background-color: #f8fafc; transition: all 0.2s ease; border-radius: 16px; }
    .table-custom tbody tr:hover { background-color: white; box-shadow: var(--shadow-sm); transform: scale(1.01); }
    .table-custom td { padding: 1rem; border: none; border-top: 1px solid transparent; border-bottom: 1px solid transparent; }
    .table-custom td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; border-left: 1px solid transparent; }
    .table-custom td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; border-right: 1px solid transparent; }
    .table-custom tbody tr:hover td { border-color: var(--border); }
</style>
@endsection
