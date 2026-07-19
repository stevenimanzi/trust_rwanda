@extends('layouts.vendor')

@section('title', 'Dashboard')

@section('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 20px;
        border: none;
        height: 100%;
    }
    .ecom-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .ecom-kpi-val {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 2px;
    }
    .ecom-kpi-lbl {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
    }
    
    .chart-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .chart-legend {
        display: flex;
        gap: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
    }
    .legend-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .ecom-table {
        width: 100%;
        font-size: 0.85rem;
    }
    .ecom-table th {
        color: #1e293b;
        font-weight: 700;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .ecom-table td {
        padding: 12px 0;
        color: #475569;
        font-weight: 600;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
    }
    
    .status-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .status-completed { background: #ecfdf5; color: #10b981; }
    .status-pending { background: #fff1f2; color: #f43f5e; }
    
    .top-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
    }
    .top-item-progress {
        height: 6px;
        background: #f1f5f9;
        border-radius: 4px;
        width: 100%;
        margin-top: 6px;
    }
    .top-item-fill {
        height: 100%;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="ecom-card d-flex align-items-center gap-3">
            <div class="ecom-kpi-icon" style="background: #f3e8ff; color: #8b5cf6;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="ecom-kpi-val">{{ number_format($totalCustomers) }}+</div>
                <div class="ecom-kpi-lbl">Total Customers</div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3">
        <div class="ecom-card d-flex align-items-center gap-3">
            <div class="ecom-kpi-icon" style="background: #fffbeb; color: #f59e0b;">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div>
                <div class="ecom-kpi-val">{{ number_format($totalProducts) }}+</div>
                <div class="ecom-kpi-lbl">Total Products</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="ecom-card d-flex align-items-center gap-3">
            <div class="ecom-kpi-icon" style="background: #fff1f2; color: #f43f5e;">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <div class="ecom-kpi-val">{{ number_format($totalOrders) }}+</div>
                <div class="ecom-kpi-lbl">Total Orders</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="ecom-card d-flex align-items-center gap-3">
            <div class="ecom-kpi-icon" style="background: #ecfdf5; color: #10b981;">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
                <div class="ecom-kpi-val">{{ number_format($totalSales) }}+</div>
                <div class="ecom-kpi-lbl">Total Sales</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Sales Trend Line Chart -->
    <div class="col-xl-8">
        <div class="ecom-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="chart-title">Sales Trend</div>
                <div class="chart-legend">
                    <div><span class="legend-dot" style="background: #8b5cf6;"></span> Current year</div>
                    <div><span class="legend-dot" style="background: #ef4444;"></span> Last year</div>
                </div>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Product Views Bar Chart -->
    <div class="col-xl-4">
        <div class="ecom-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="chart-title">Product Views</div>
                <div class="chart-legend" style="flex-direction: column; gap: 4px; font-size: 0.65rem;">
                    <div><span class="legend-dot" style="background: #8b5cf6;"></span> This Week</div>
                    <div><span class="legend-dot" style="background: #ef4444;"></span> Last Week</div>
                </div>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="productViewsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- All Orders Table -->
    <div class="col-xl-8">
        <div class="ecom-card">
            <div class="chart-title mb-4">All Orders</div>
            <div class="table-responsive">
                <table class="ecom-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Orders ID</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($recentOrders->isEmpty())
                        <tr><td colspan="6" class="text-center py-4">No recent orders found.</td></tr>
                        @else
                            @foreach($recentOrders as $ro)
                            <tr>
                                <td>
                                    <img src="{{ $ro->product_image ? asset('assets/uploads/products/'.$ro->product_image) : 'https://placehold.co/40x40/f1f5f9/94a3b8?text=Img' }}" 
                                         style="width: 36px; height: 36px; border-radius: 6px; object-fit: cover;" alt="Product">
                                </td>
                                <td>#{{ str_pad($ro->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $ro->customer_name ?? 'Guest' }}</td>
                                <td><i class="bi bi-calendar3 text-muted me-1"></i> {{ date('j M y', strtotime($ro->created_at)) }}</td>
                                <td>RWF {{ number_format($ro->total_price) }}</td>
                                <td>
                                    <span class="status-pill {{ strtolower($ro->status) == 'delivered' ? 'status-completed' : 'status-pending' }}">
                                        {{ strtolower($ro->status) == 'delivered' ? 'Completed' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Sold Items -->
    <div class="col-xl-4">
        <div class="ecom-card">
            <div class="chart-title mb-4">Top Sold Items</div>
            
            <div class="d-flex flex-column gap-3 mt-4">
                @if($topSoldItems->isEmpty())
                    <div class="text-center py-4 text-muted">No sold items found.</div>
                @else
                    @php 
                        $colors = ['#8b5cf6', '#f59e0b', '#ef4444', '#10b981']; 
                    @endphp
                    @foreach($topSoldItems as $index => $item)
                    @php $color = $colors[$index % count($colors)]; @endphp
                    <div>
                        <div class="top-item-row">
                            <span class="text-truncate" style="max-width: 200px;">{{ $item->title }}</span>
                            <span>{{ $item->percentage }}%</span>
                        </div>
                        <div class="top-item-progress">
                            <div class="top-item-fill" style="width: {{ $item->percentage }}%; background: {{ $color }};"></div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // 1. Sales Trend Line Chart
    const ctxSales = document.getElementById('salesTrendChart');
    if (ctxSales) {
        new Chart(ctxSales.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'Current year',
                        data: {!! json_encode($salesTrendCurrent) !!},
                        borderColor: '#8b5cf6',
                        backgroundColor: 'transparent',
                        borderWidth: 2.5,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Last year',
                        data: {!! json_encode($salesTrendLast) !!},
                        borderColor: '#ef4444',
                        backgroundColor: 'transparent',
                        borderWidth: 2.5,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    }

    // 2. Product Views Double Bar Chart
    const ctxViews = document.getElementById('productViewsChart');
    if (ctxViews) {
        new Chart(ctxViews.getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($weekDays) !!},
                datasets: [
                    {
                        label: 'This Week',
                        data: {!! json_encode($thisWeekViews) !!},
                        backgroundColor: '#8b5cf6',
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Last Week',
                        data: {!! json_encode($lastWeekViews) !!},
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: false, drawBorder: false },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    }
});
</script>
@endsection
