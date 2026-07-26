@extends('layouts.vendor')

@section('title', 'Financial Reports | Vendor Dashboard')

@section('styles')
<style>
    /* Premium KPI Cards */
    .report-kpi-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 10px;
    }
    .kpi-title {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .kpi-value {
        color: #0f172a;
        font-size: clamp(1.2rem, 1.8vw, 1.8rem);
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 0;
        word-break: break-word;
        line-height: 1.2;
    }
    .bg-indigo-subtle { background-color: #e0e7ff; color: #4f46e5; }
    .bg-emerald-subtle { background-color: #d1fae5; color: #10b981; }
    .bg-amber-subtle { background-color: #fef3c7; color: #d97706; }
    .bg-rose-subtle { background-color: #ffe4e6; color: #e11d48; }

    /* Filters Box */
    .filters-box {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }
    .filter-btn {
        padding: 8px 16px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .filter-btn:hover { background: #e2e8f0; color: #0f172a; }
    .filter-btn.active {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }
    
    /* Charts and Tables */
    .table-container {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .custom-table {
        border-collapse: collapse;
        width: 100%;
    }
    .custom-table th {
        background: transparent;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border-bottom: 2px solid #e2e8f0;
        padding: 12px 20px;
    }
    .custom-table tbody tr {
        /* Flat enterprise style */
    }
    .custom-table tbody tr:nth-child(even) td {
        background-color: #f8fafc !important; /* Lighter zebra stripe for standard tables */
    }
    .custom-table tbody tr:nth-child(odd) td {
        background-color: #ffffff !important;
    }
    .custom-table td {
        padding: 16px 20px;
        vertical-align: middle;
        color: #334155;
        font-weight: 500;
        border-bottom: 1px solid #e2e8f0;
    }
    
    /* Print Styles - High Quality PDF Export */
    @media print {
        @page { size: A4 portrait; margin: 1.5cm; }
        body { background: white; margin: 0; padding: 0; }
        .sidebar, .navbar, .filters-box, .btn-print { display: none !important; }
        .hz-main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .report-kpi-card, .table-container {
            box-shadow: none !important;
            border: 1px solid #e2e8f0 !important;
            break-inside: avoid;
        }
        .row { display: flex !important; flex-wrap: nowrap !important; }
        .col-md-3 { width: 25% !important; }
        .kpi-value { font-size: 1.5rem !important; }
        .print-header { display: block !important; margin-bottom: 30px; text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .print-logo { font-size: 24px; font-weight: 900; }
    }
    .print-header { display: none; }
</style>
@endsection

@section('content')

<!-- Print Header (Only visible when printing) -->
<div class="print-header">
    <div class="print-logo">Trust Rwanda | Business Report</div>
    <h4>{{ $reportTitle }}</h4>
    <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
</div>

<div id="top-header-section" class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Financial Reports</h2>
        <p class="text-muted mb-0">Analyze your business performance and earnings.</p>
    </div>
    <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm btn-print d-flex align-items-center gap-2">
        <i class="bi bi-printer"></i> Export PDF
    </button>
</div>

<!-- Filters -->
<div class="filters-box">
    <span class="fw-bold text-dark me-2">Date Range:</span>
    <a href="{{ route('vendor.reports', ['period' => 'daily']) }}" class="filter-btn {{ $period == 'daily' ? 'active' : '' }}">Today</a>
    <a href="{{ route('vendor.reports', ['period' => 'weekly']) }}" class="filter-btn {{ $period == 'weekly' ? 'active' : '' }}">This Week</a>
    <a href="{{ route('vendor.reports', ['period' => 'monthly']) }}" class="filter-btn {{ $period == 'monthly' ? 'active' : '' }}">This Month</a>
    <a href="{{ route('vendor.reports', ['period' => 'yearly']) }}" class="filter-btn {{ $period == 'yearly' ? 'active' : '' }}">This Year</a>
    
    <div class="ms-auto d-flex gap-2">
        <form action="{{ route('vendor.reports') }}" method="GET" class="d-flex gap-2 align-items-center">
            <input type="hidden" name="period" value="custom">
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}" required>
            <span>to</span>
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}" required>
            <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3 fw-bold">Apply</button>
        </form>
    </div>
</div>

<h4 id="report-title-heading" class="fw-bold mb-4">{{ $reportTitle }}</h4>

<!-- KPI Cards -->
<div class="row g-4 mb-4" id="kpi-section">
    <div class="col-md-3">
        <div class="report-kpi-card">
            <div class="kpi-icon bg-indigo-subtle"><i class="bi bi-wallet2"></i></div>
            <div class="kpi-title">Gross Sales</div>
            <div class="kpi-value">{{ number_format($totalGrossSales) }}<span class="fs-5 text-muted ms-1">RWF</span></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="report-kpi-card">
            <div class="kpi-icon bg-emerald-subtle"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="kpi-title">Net Profit (est.)</div>
            <div class="kpi-value text-success">{{ number_format($netProfit) }}<span class="fs-5 text-muted ms-1">RWF</span></div>
            <small class="text-muted mt-2" style="font-size: 0.75rem;">Gross sales - 5% platform fee</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="report-kpi-card">
            <div class="kpi-icon bg-amber-subtle"><i class="bi bi-bag-check"></i></div>
            <div class="kpi-title">Total Orders</div>
            <div class="kpi-value">{{ number_format($totalOrders) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="report-kpi-card">
            <div class="kpi-icon bg-rose-subtle"><i class="bi bi-box-seam"></i></div>
            <div class="kpi-title">Items Sold</div>
            <div class="kpi-value">{{ number_format($totalItemsSold) }}</div>
        </div>
    </div>
</div>


<!-- Data Table -->
<div class="table-container">
    <h5 class="fw-bold mb-4">Detailed Transactions</h5>
    <div class="table-responsive">
        <table class="table custom-table mb-0">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesData as $item)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{ date('M d, Y', strtotime($item->date)) }}</div>
                        <div class="small text-muted">{{ date('h:i A', strtotime($item->date)) }}</div>
                    </td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border">{{ $item->quantity }}</span></td>
                    <td class="text-end text-muted">{{ number_format($item->unit_price) }} RWF</td>
                    <td class="text-end fw-bold text-dark">{{ number_format($item->total_price) }} RWF</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted fw-bold mb-0">No sales data found for the selected period.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
