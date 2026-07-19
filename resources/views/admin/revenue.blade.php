@extends('layouts.admin')

@section('title', 'Financial Intelligence')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h4 class="fw-900 m-0 text-dark">FINANCIAL INTELLIGENCE</h4>
    <div class="d-flex gap-2 no-print">
        <a href="{{ route('admin.revenue.index', ['range' => '7']) }}" class="filter-pill {{ $range=='7'?'active':'' }}">7D</a>
        <a href="{{ route('admin.revenue.index', ['range' => '30']) }}" class="filter-pill {{ $range=='30'?'active':'' }}">30D</a>
        <a href="{{ route('admin.revenue.index', ['range' => 'all']) }}" class="filter-pill {{ $range=='all'?'active':'' }}">ALL</a>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-4">
        <div class="finance-card card-glow-primary text-white">
            <div class="icon-box"><i class="bi bi-bank"></i></div>
            <h6 class="text-white-50 small fw-900 uppercase">Net Yield Overview</h6>
            <h2 class="fw-900 mb-0">{{ number_format($netProfit) }} <small class="fs-6 opacity-75">RWF</small></h2>
            <div class="mt-4 p-2 rounded-3 bg-white bg-opacity-10 text-center small fw-bold">Aggregate Profitability</div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="finance-card" style="border-left: 5px solid #10b981;">
            <div class="icon-box" style="color:#10b981;"><i class="bi bi-calendar-check"></i></div>
            <h6 class="text-muted small fw-900 text-uppercase">Recurring Subscriptions</h6>
            <h3 class="fw-900 mb-0 text-dark">{{ number_format($subIncome) }} <small class="fs-6 fw-normal text-muted">RWF</small></h3>
            <p class="small mt-3 mb-0 text-success fw-bold uppercase">Direct Shop Fees</p>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="finance-card" style="border-left: 5px solid #f59e0b;">
            <div class="icon-box" style="color:#f59e0b;"><i class="bi bi-percent"></i></div>
            <h6 class="text-muted small fw-900 text-uppercase">Transactional Commissions</h6>
            <h3 class="fw-900 mb-0 text-dark">{{ number_format($commIncome) }} <small class="fs-6 fw-normal text-muted">RWF</small></h3>
            <p class="small mt-3 mb-0 text-muted">From {{ number_format($gmv) }} GMV</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-7">
        <div class="finance-card">
            <h5 class="fw-900 mb-5 text-dark">REVENUE MIX ANALYSIS</h5>
            <div style="height: 320px;"><canvas id="revenueBreakdown"></canvas></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="finance-card">
            <h5 class="fw-900 mb-4 text-dark">STREAM VELOCITY</h5>
            <div class="mb-4">
                @php $subWeight = ($netProfit > 0) ? round(($subIncome/$netProfit)*100) : 0; @endphp
                <div class="d-flex justify-content-between mb-2"><span class="small fw-900 text-muted">SUBSCRIPTION WEIGHT</span><span class="fw-900 text-dark">{{ $subWeight }}%</span></div>
                <div class="progress bg-light" style="height: 10px; border-radius: 50px;"><div class="progress-bar bg-success" style="width: {{ $subWeight }}%"></div></div>
            </div>
            <div class="mb-4">
                @php $commWeight = ($netProfit > 0) ? round(($commIncome/$netProfit)*100) : 0; @endphp
                <div class="d-flex justify-content-between mb-2"><span class="small fw-900 text-muted">COMMISSION WEIGHT</span><span class="fw-900 text-dark">{{ $commWeight }}%</span></div>
                <div class="progress bg-light" style="height: 10px; border-radius: 50px;"><div class="progress-bar bg-warning" style="width: {{ $commWeight }}%"></div></div>
            </div>
            <div class="mt-auto p-3 bg-indigo-subtle bg-opacity-10 border border-primary border-opacity-10 rounded-4">
                <p class="small text-muted mb-0 italic">Commissions calculated at fixed {{ $commissionRate*100 }}% on total order fulfillment value.</p>
            </div>
        </div>
    </div>
</div>

<div class="finance-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h5 class="fw-900 m-0 text-dark text-uppercase">LIVE TRANSACTION LEDGER</h5>
        <button class="btn btn-primary rounded-pill px-4 fw-800 btn-sm shadow-lg no-print" onclick="window.print()"><i class="bi bi-printer me-2"></i>EXPORT REPORT</button>
    </div>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>EVENT_TIMESTAMP</th>
                    <th>SOURCE_ENTITY</th>
                    <th>FLOW_TYPE</th>
                    <th class="text-end">CREDIT_AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($ledger))
                    <tr><td colspan="4" class="text-center py-5 opacity-50 fw-bold">NO RECENT CREDITS DETECTED</td></tr>
                @else 
                    @foreach($ledger as $row)
                    <tr>
                        <td class="small fw-800 text-muted">{{ date('d M, Y • H:i', strtotime($row['date'])) }}</td>
                        <td><div class="fw-900 text-dark fs-6">{{ $row['source'] }}</div></td>
                        <td><span class="badge-income {{ $row['type']=='Subscription'?'bg-neon-green':'bg-neon-amber' }}">{{ $row['type'] }}</span></td>
                        <td class="text-end fw-900 text-primary fs-5">+ {{ number_format($row['amount']) }} RWF</td>
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
        const ctx = document.getElementById('revenueBreakdown');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Subscriptions', 'Commissions'],
                    datasets: [{
                        data: [{{ (float)$subIncome }}, {{ (float)$commIncome }}],
                        backgroundColor: ['#6366f1', '#f59e0b'],
                        borderWidth: 0,
                        hoverOffset: 30
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '82%',
                    plugins: { 
                        legend: { position: 'bottom', labels: { color: '#1e293b', font: { weight: 'bold', size: 14 }, padding: 30, usePointStyle: true } } 
                    }
                }
            });
        }
    })();
</script>
@endsection

@section('styles')
<style>
    .finance-card { background: var(--admin-card); border-radius: 24px; padding: 1.75rem; border: none; box-shadow: var(--shadow-sm); height: 100%; transition: all 0.3s ease; position: relative; overflow: hidden; }
    .finance-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
    .card-glow-primary { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color: white; }

    .icon-box { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 1.5rem; background: #f1f5f9; }
    .card-glow-primary .icon-box { background: rgba(255,255,255,0.2); color: white; }
    
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
    
    .badge-income { font-size: 0.75rem; font-weight: 800; padding: 8px 18px; border-radius: 50px; text-transform: uppercase; }
    .bg-neon-green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .bg-neon-amber { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
</style>
@endsection
