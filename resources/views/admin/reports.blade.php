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

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="pro-card">
            <h5 class="fw-900 text-dark mb-4"><i class="bi bi-graph-up-arrow"></i> FULFILLMENT TREND</h5>
            <div style="height: 300px;"><canvas id="revenueTrendChart"></canvas></div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="pro-card">
            <h5 class="fw-900 text-dark mb-4"><i class="bi bi-tag-fill"></i> CATEGORY MIX</h5>
            <div style="height: 300px;"><canvas id="categoryBreakdownChart"></canvas></div>
        </div>
    </div>
</div>

<div class="pro-card mb-4">
    <h5 class="fw-900 text-dark mb-4 border-bottom pb-2"><i class="bi bi-shop"></i> TOP MERCHANT DISPATCH</h5>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Merchant Brand</th>
                    <th>Fulfillments</th>
                    <th class="text-end">Volume Revenue</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($topVendors))
                    <tr><td colspan="3" class="text-center py-4 opacity-50 fw-bold">NO SALES DATA CURRENTLY IN RANGE</td></tr>
                @else
                    @foreach ($topVendors as $row)
                    <tr>
                        <td><div class="fw-900 text-dark">{{ $row->shop_name }}</div></td>
                        <td><span class="badge bg-indigo-subtle text-primary border border-primary border-opacity-10 px-3 py-1 rounded-pill fw-bold">{{ $row->order_count }} Fulfilled</span></td>
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
                        borderColor: '#6366f1',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
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
                        backgroundColor: ['#6366f1', '#ec4899', '#10b981', '#f59e0b', '#3b82f6', '#8b5cf6'],
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
    .pro-card { background: var(--admin-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.75rem; box-shadow: var(--shadow-sm); height: 100%; transition: all 0.3s ease; }
    .pro-card:hover { box-shadow: var(--shadow-md); border-color: #cbd5e1; }
    
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
