@extends('layouts.property_owner')

@section('title', 'Dashboard')

@section('content')
@section('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 20px;
        border: none;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .kpi-title {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .kpi-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .kpi-trend {
        font-size: 0.75rem;
        font-weight: 600;
    }
    .kpi-trend.up { color: #10b981; }
    .kpi-trend.down { color: #f43f5e; }
    
    .chart-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-800 text-dark mb-1">Property Dashboard</h3>
        <p class="text-muted fw-medium mb-0">Track all your real estate activity in one place</p>
    </div>
    <div class="d-flex gap-2">
        <button class="hz-dropdown-btn border shadow-sm"><i class="bi bi-calendar3"></i> Last month <i class="bi bi-chevron-down ms-2"></i></button>
        <button class="hz-dropdown-btn border shadow-sm"><i class="bi bi-houses"></i> All properties <i class="bi bi-chevron-down ms-2"></i></button>
    </div>
</div>

<!-- KPI Metrics -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="ecom-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-title">Active Listings</div>
                    <div class="kpi-value">{{ $totalProperties }}</div>
                    <div class="kpi-trend up"><i class="bi bi-arrow-up-short"></i> 5.2%</div>
                </div>
                <div class="hz-icon-btn-light" style="background: #eef2ff; color: #4f46e5; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-house-check fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3">
        <div class="ecom-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-title">Total Inquiries</div>
                    <div class="kpi-value">{{ $totalInquiries }}</div>
                    <div class="kpi-trend up"><i class="bi bi-arrow-up-short"></i> 12.8%</div>
                </div>
                <div class="hz-icon-btn-light" style="background: #ecfdf5; color: #10b981; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-chat-dots fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="ecom-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-title">Total Views</div>
                    <div class="kpi-value">{{ number_format($totalViews) }}</div>
                    <div class="kpi-trend down"><i class="bi bi-arrow-down-short"></i> 1.5%</div>
                </div>
                <div class="hz-icon-btn-light" style="background: #fdf2f8; color: #db2777; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-eye fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="ecom-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-title">Avg Price</div>
                    <div class="kpi-value" style="font-size: 1.2rem;">RWF {{ number_format($avgPrice) }}</div>
                    <div class="kpi-trend up"><i class="bi bi-arrow-up-short"></i> 2.4%</div>
                </div>
                <div class="hz-icon-btn-light" style="background: #fffbeb; color: #d97706; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-cash-stack fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart Section -->
    <div class="col-xl-8">
        <div class="ecom-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="chart-title">Views activity</div>
                    <div class="text-muted small">Overview all tracked property views</div>
                </div>
            </div>
            
            <div class="d-flex gap-4 mb-3">
                <div>
                    <div class="kpi-value mb-0">{{ number_format($points[6]['val']) }} <span class="fs-6 text-muted fw-normal">Views</span></div>
                    <div class="kpi-trend up"><i class="bi bi-arrow-up-short"></i> 24%</div>
                </div>
            </div>
            
            <div class="chart-container" style="height: 250px; position: relative;">
                <canvas id="propertyViewsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Right Side Gauges & Stats -->
    <div class="col-xl-4">
        <div class="ecom-card d-flex flex-column gap-4">
            
            <!-- Project Progress (Store progress) -->
            <div>
                <div class="mb-3">
                    <div class="chart-title fs-6">Listing progress</div>
                    <div class="text-muted small">An overview of active portfolio</div>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="hz-progress-track flex-grow-1 mb-0 bg-light rounded-pill overflow-hidden" style="height: 12px; display:flex;">
                        <div class="bg-primary" style="width: {{ $rentRatio }}%; height:100%;"></div>
                        <div class="bg-warning" style="width: {{ $saleRatio }}%; height:100%;"></div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 0.8rem;">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:8px; height:8px; border-radius:50%; background:var(--hz-primary);"></span> For Rent ({{ $rentRatio }}%)
                    </div>
                    <span class="fw-bold text-dark">{{ $rentCount }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted" style="font-size: 0.8rem;">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:8px; height:8px; border-radius:50%; background:var(--bs-warning);"></span> For Sale ({{ $saleRatio }}%)
                    </div>
                    <span class="fw-bold text-dark">{{ $saleCount }}</span>
                </div>
            </div>
            
            <hr class="my-0 border-light">

            <!-- Work activity (Gauges) -->
            <div>
                <div class="mb-3">
                    <div class="chart-title fs-6">Portfolio Value</div>
                    <div class="text-muted small">Total asset value estimates</div>
                </div>
                
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="mb-3">
                            <div class="text-muted fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">AVG PRICE</div>
                            <div class="fw-800 text-dark fs-5">{{ $avgPrice > 0 ? number_format($avgPrice / 1000000, 1) . 'M' : '0' }}</div>
                        </div>
                    </div>
                    
                    <div style="position: relative; width: 100px; height: 100px;">
                        <svg viewBox="0 0 100 100" style="transform: rotate(-90deg); width: 100%; height: 100%;">
                            <circle cx="50" cy="50" r="40" fill="none" stroke="var(--hz-border-strong)" stroke-width="8"></circle>
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#f59e0b" stroke-width="8" stroke-dasharray="188 251.2" stroke-linecap="round"></circle>
                        </svg>
                        <div style="position: absolute; top:50%; left:50%; transform: translate(-50%, -50%); text-align:center;">
                            <div class="fw-900 fs-5" style="line-height:1;">{{ $totalValue > 0 ? number_format($totalValue / 1000000, 1) : '0' }}</div>
                            <div class="text-muted" style="font-size:0.65rem; font-weight:700;">mil RWF</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Task Overview (Latest Properties) -->
<div class="ecom-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="chart-title">Latest Listings</div>
            <div class="text-muted small">Overview spread of all properties</div>
        </div>
    </div>
    
    <div class="row g-3">
        @if ($latestDbProperties->isEmpty())
            <div class="col-12 text-center py-5">
                <div class="hz-icon-btn-light mx-auto mb-3" style="width: 60px; height: 60px; font-size: 2rem;">
                    <i class="bi bi-houses"></i>
                </div>
                <p class="text-muted fw-bold small m-0">No properties found.</p>
                <a href="{{ route('property_owner.properties.create') }}" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold mt-3">Add Property</a>
            </div>
        @else
            @foreach ($latestDbProperties->take(4) as $prop)
                @php
                    $type = strtolower($prop->property_type);
                    $iconClass = 'bi-house-heart';
                    $pillStyle = 'background: #ecfdf5; color: #10b981;';
                    
                    if ($type === 'apartment') {
                        $iconClass = 'bi-building';
                        $pillStyle = 'background: #eef2ff; color: #4f46e5;';
                    } elseif ($type === 'land') {
                        $iconClass = 'bi-bounding-box-circles';
                        $pillStyle = 'background: #fffbeb; color: #d97706;';
                    }
                @endphp
                <div class="col-md-3">
                    <div class="border rounded-3 p-3 h-100 bg-light" style="cursor: pointer; border-color: #f1f5f9 !important;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="hz-icon-btn-light shadow-sm" style="width: 32px; height: 32px; background: #fff;">
                                <i class="bi {{ $iconClass }} text-muted"></i>
                            </div>
                            <span class="px-2 py-1 rounded-pill fw-bold" style="font-size: 0.65rem; text-transform: uppercase; {{ $pillStyle }}">{{ ucfirst($prop->listing_type) }}</span>
                        </div>
                        <div class="fw-bold text-dark text-truncate mb-1">{{ $prop->title }}</div>
                        <div class="text-muted small mb-3 text-truncate">{{ $prop->address }}</div>
                        <div class="fw-800 text-dark">{{ number_format($prop->price) }} <span class="fw-normal text-muted" style="font-size:0.75rem;">RWF</span></div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('propertyViewsChart');
    if (!ctx) return;
    
    // Stacked Bar Chart mimicking HorizonHub
    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($days ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
            datasets: [{
                label: 'Views',
                data: {!! json_encode(array_column($points ?? [], 'val')) !!},
                backgroundColor: function(context) {
                    const index = context.dataIndex;
                    // Highlight today/latest bar in Horizon green
                    return index === 6 ? '#10b981' : '#e2e8f0';
                },
                borderRadius: 6,
                borderSkipped: false,
                barThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                    bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                    displayColors: false,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', borderDash: [4, 4], drawBorder: false },
                    ticks: { color: '#94a3b8', font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#94a3b8', font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 } }
                }
            },
            interaction: { mode: 'index', intersect: false }
        }
    });
});
</script>
@endsection
