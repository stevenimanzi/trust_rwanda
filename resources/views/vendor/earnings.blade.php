@extends('layouts.vendor')

@section('title', 'Earnings')

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
    .hz-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .hz-kpi-icon.purple { background: var(--hz-primary-light); color: var(--hz-primary); }
    .hz-kpi-icon.yellow { background: #fef3c7; color: #d97706; }
    .hz-kpi-icon.green { background: var(--hz-secondary-light); color: var(--hz-secondary); }
    .hz-kpi-icon.red { background: #fee2e2; color: #dc2626; }
    
    .ecom-table {
        width: 100%;
        font-size: 0.85rem;
    }
    .ecom-table th {
        color: #1e293b;
        font-weight: 700;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        text-transform: uppercase;
        font-size: 0.75rem;
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
    .st-cleared { background: #ecfdf5; color: #10b981; }
    .st-pending { background: #fff7ed; color: #c2410c; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="chart-title fs-4 mb-0">Earnings & Payouts</div>
</div>

<div class="row g-4 mb-4">
    <!-- Net Available Balance -->
    <div class="col-12 col-md-4">
        <div class="ecom-card d-flex align-items-center gap-3">
            <div class="hz-kpi-icon purple">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase">Net Balance</div>
                <div class="fs-4 fw-bold text-dark">RWF {{ number_format($availableBalance ?? 0) }}</div>
            </div>
        </div>
    </div>
    
    <!-- Total Earnings -->
    <div class="col-12 col-md-4">
        <div class="ecom-card d-flex align-items-center gap-3">
            <div class="hz-kpi-icon green">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase">Total Earnings</div>
                <div class="fs-4 fw-bold text-dark">RWF {{ number_format($totalEarnings ?? 0) }}</div>
            </div>
        </div>
    </div>
    
    <!-- Pending Clearance -->
    <div class="col-12 col-md-4">
        <div class="ecom-card d-flex align-items-center gap-3">
            <div class="hz-kpi-icon yellow">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase">Pending Clearance</div>
                <div class="fs-4 fw-bold text-dark">RWF {{ number_format($pendingClearance ?? 0) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="ecom-card">
    <div class="chart-title fs-6 mb-4">Transaction History</div>
    
    <div class="table-responsive">
        <table class="ecom-table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($transactions) && count($transactions) > 0)
                    @foreach($transactions as $txn)
                    <tr>
                        <td class="fw-bold text-dark">#TXN-{{ str_pad($txn->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ date('j M y, h:i A', strtotime($txn->created_at)) }}</td>
                        <td>
                            @if($txn->type == 'sale')
                                <span class="text-success fw-bold"><i class="bi bi-arrow-down-left-circle"></i> Sale Revenue</span>
                            @else
                                <span class="text-danger fw-bold"><i class="bi bi-arrow-up-right-circle"></i> Withdrawal</span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark">RWF {{ number_format($txn->amount) }}</td>
                        <td>
                            <span class="status-pill {{ strtolower($txn->status) == 'cleared' ? 'st-cleared' : 'st-pending' }}">
                                {{ $txn->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="5" class="text-center py-5 text-muted">No transactions recorded yet.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
