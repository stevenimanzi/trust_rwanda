@extends('layouts.admin')

@section('title', 'Intelligence Center')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-800 text-dark mb-1">Welcome back, {{ auth()->user()->full_name ?? 'Admin' }}! 👋</h3>
        <p class="text-muted fw-medium mb-0">Track all your marketplace activity in one place</p>
    </div>
    <div class="dropdown">
        @php
            $rangeText = 'Last 6 Months';
            if(isset($range)) {
                if($range == '7') $rangeText = 'Last 7 Days';
                elseif($range == '30') $rangeText = 'Last 30 Days';
                elseif($range == '90') $rangeText = 'Last 3 Months';
                elseif($range == 'all') $rangeText = 'All Time';
            }
        @endphp
        <button class="hz-dropdown-btn border shadow-sm" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-calendar3"></i> {{ $rangeText }} <i class="bi bi-chevron-down ms-2"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-1">
            <li><a class="dropdown-item py-2 fw-medium {{ (isset($range) && $range == '7') ? 'active bg-primary text-white' : '' }}" href="?range=7">Last 7 Days</a></li>
            <li><a class="dropdown-item py-2 fw-medium {{ (isset($range) && $range == '30') ? 'active bg-primary text-white' : '' }}" href="?range=30">Last 30 Days</a></li>
            <li><a class="dropdown-item py-2 fw-medium {{ (isset($range) && $range == '90') ? 'active bg-primary text-white' : '' }}" href="?range=90">Last 3 Months</a></li>
            <li><a class="dropdown-item py-2 fw-medium {{ (!isset($range) || $range == '180') ? 'active bg-primary text-white' : '' }}" href="?range=180">Last 6 Months</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2 fw-medium {{ (isset($range) && $range == 'all') ? 'active bg-primary text-white' : '' }}" href="?range=all">All Time</a></li>
        </ul>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div>
                    <div class="hz-card-subtitle">Marketplace GTV</div>
                </div>
                <div class="hz-icon-btn shadow-sm" style="background: var(--hz-primary-light); color: var(--hz-primary);"><i class="bi bi-wallet2"></i></div>
            </div>
            <div class="hz-kpi-value mb-2"><span id="kpi-revenue">{{ number_format($revenue) }}</span> <span class="fs-6 text-muted">RWF</span></div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div>
                    <div class="hz-card-subtitle">Verified Merchants</div>
                </div>
                <div class="hz-icon-btn shadow-sm" style="background: var(--hz-secondary-light); color: var(--hz-secondary);"><i class="bi bi-shop"></i></div>
            </div>
            <div class="hz-kpi-value mb-2" id="kpi-merchants">{{ number_format($totalVendors) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div>
                    <div class="hz-card-subtitle">Total Transactions</div>
                </div>
                <div class="hz-icon-btn shadow-sm" style="background: var(--hz-tertiary-light); color: var(--hz-tertiary);"><i class="bi bi-cart-check"></i></div>
            </div>
            <div class="hz-kpi-value mb-2" id="kpi-transactions">{{ number_format($totalOrders) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div>
                    <div class="hz-card-subtitle">Global User Base</div>
                </div>
                <div class="hz-icon-btn shadow-sm" style="background: #f3e8ff; color: #9333ea;"><i class="bi bi-people"></i></div>
            </div>
            <div class="hz-kpi-value mb-2" id="kpi-users">{{ number_format($totalUsers) }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="hz-card">
            <div class="hz-card-header">
                <div>
                    <div class="hz-card-title">KPI performance</div>
                    <div class="hz-card-subtitle">Your platform revenue performance</div>
                </div>
                <div class="dropdown">
                    <button class="hz-dropdown-btn border" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-calendar"></i> {{ $rangeText }} <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-1">
                        <li><a class="dropdown-item py-2 fw-medium {{ (isset($range) && $range == '7') ? 'active bg-primary text-white' : '' }}" href="?range=7">Last 7 Days</a></li>
                        <li><a class="dropdown-item py-2 fw-medium {{ (isset($range) && $range == '30') ? 'active bg-primary text-white' : '' }}" href="?range=30">Last 30 Days</a></li>
                        <li><a class="dropdown-item py-2 fw-medium {{ (isset($range) && $range == '90') ? 'active bg-primary text-white' : '' }}" href="?range=90">Last 3 Months</a></li>
                        <li><a class="dropdown-item py-2 fw-medium {{ (!isset($range) || $range == '180') ? 'active bg-primary text-white' : '' }}" href="?range=180">Last 6 Months</a></li>
                        <li><a class="dropdown-item py-2 fw-medium {{ (isset($range) && $range == 'all') ? 'active bg-primary text-white' : '' }}" href="?range=all">All Time</a></li>
                    </ul>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="hz-kpi-value" style="font-size:2.5rem;">{{ number_format($revenue) }} <span class="fs-6 text-muted fw-normal">RWF</span></div>
            </div>
            <div class="chart-container" style="height: 280px; position: relative;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="hz-card">
            <div class="hz-card-header mb-3">
                <div>
                    <div class="hz-card-title">Pending Vendors</div>
                    <div class="hz-card-subtitle">Merchants waiting approval</div>
                </div>
                <a href="{{ route('admin.users.index', ['role' => 'vendor']) }}" class="hz-dropdown-btn border text-decoration-none">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            
            <div class="list-group list-group-flush border-0">
                @if($pendingVendors->isEmpty())
                <div class="text-center py-5">
                    <div class="hz-icon-btn mx-auto mb-3" style="width: 60px; height: 60px; background: var(--hz-secondary-light); color: var(--hz-secondary); font-size: 2rem;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <p class="hz-text-muted fw-bold small m-0">All clear! No pending vendors.</p>
                </div>
                @else
                    @foreach($pendingVendors as $v)
                        <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-800 shadow-sm flex-shrink-0" style="width:40px; height:40px; font-size: 1rem; background: var(--hz-primary-light); color: var(--hz-primary);">
                                    {{ strtoupper(substr($v->shop_name ?: $v->full_name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-bold text-truncate text-dark" style="font-size: 0.95rem;">{{ $v->shop_name ?: 'Vendor' }}</div>
                                    <div class="text-muted fw-medium text-truncate" style="font-size: 0.75rem;">{{ $v->email }}</div>
                                </div>
                            </div>
                            <span class="hz-status-pill progress cursor-pointer" onclick="window.location.href='{{ route('admin.users.index') }}'">Verify</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<div class="hz-card">
    <div class="hz-card-header">
        <div>
            <div class="hz-card-title">Recent Transactions</div>
            <div class="hz-card-subtitle">Overview of the latest global orders</div>
        </div>
        <a href="{{ route('admin.reports') }}" class="hz-dropdown-btn border text-decoration-none">View Details <i class="bi bi-arrow-right"></i></a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-borderless align-middle">
            <thead class="border-bottom" style="color: var(--hz-text-muted); font-size: 0.85rem; text-transform: uppercase;">
                <tr>
                    <th class="py-3 px-2">Order Details</th>
                    <th class="py-3 px-2">Customer</th>
                    <th class="py-3 px-2">Amount</th>
                    <th class="py-3 px-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @if($recentOrders->isEmpty())
                <tr><td colspan="4" class="text-center text-muted fw-bold py-5">No recent transactions to display.</td></tr>
                @else
                @foreach($recentOrders as $o)
                <tr class="border-bottom border-light">
                    <td class="py-3 px-2">
                        <div class="fw-bold text-dark mb-1">#{{ str_pad($o->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ date('M d, Y • H:i', strtotime($o->created_at)) }}</div>
                    </td>
                    <td class="py-3 px-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-light fw-bold text-dark flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                {{ strtoupper(substr($o->customer->full_name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="fw-600 text-dark text-truncate" style="max-width: 150px; font-size: 0.9rem;">{{ $o->customer->full_name ?? 'Guest User' }}</div>
                        </div>
                    </td>
                    <td class="py-3 px-2">
                        <div class="fw-800 text-dark">{{ number_format($o->total_amount) }} <span class="fw-normal text-muted" style="font-size: 0.75rem;">RWF</span></div>
                    </td>
                    <td class="py-3 px-2">
                        @if($o->delivery_status == 'delivered')
                            <span class="hz-status-pill completed">Completed</span>
                        @elseif($o->delivery_status == 'cancelled' || $o->delivery_status == 'returned')
                            <span class="hz-status-pill" style="background:#fee2e2; color:#ef4444;">Cancelled</span>
                        @else
                            <span class="hz-status-pill progress">In progress</span>
                        @endif
                    </td>
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
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Create an elegant Horizon gradient (Orange to Transparent)
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(255, 106, 62, 0.4)'); // hz-primary
    gradient.addColorStop(1, 'rgba(255, 106, 62, 0)');

    let revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Revenue (RWF)',
                data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0]) !!},
                borderColor: '#ff6a3e', // hz-primary
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#ff6a3e',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Smooth curves
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
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
                    grid: { color: '#f1f5f9', borderDash: [5, 5], drawBorder: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                            if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                            return value;
                        }
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#94a3b8', font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 } }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });

    // Real-Time Polling Engine (Every 15 seconds)
    setInterval(() => {
        const currentUrl = new URL(window.location.href);
        const activeRange = currentUrl.searchParams.get('range') || '180';
        fetch(`{{ route('admin.dashboard') }}?ajax=1&range=${activeRange}`)
            .then(res => res.json())
            .then(data => {
                // Update KPIs seamlessly
                if(data.kpis) {
                    document.getElementById('kpi-revenue').innerText = data.kpis.revenue;
                    document.getElementById('kpi-merchants').innerText = data.kpis.totalVendors;
                    document.getElementById('kpi-transactions').innerText = data.kpis.totalOrders;
                    document.getElementById('kpi-users').innerText = data.kpis.totalUsers;
                }
                
                // Update Chart smoothly
                if(data.chartLabels && data.chartData) {
                    revenueChart.data.labels = data.chartLabels;
                    revenueChart.data.datasets[0].data = data.chartData;
                    revenueChart.update();
                }
            })
            .catch(e => console.error("Real-time sync failed:", e));
    }, 15000);
});
</script>
@endsection
