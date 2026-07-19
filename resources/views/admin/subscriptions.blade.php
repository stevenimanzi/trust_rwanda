@extends('layouts.admin')

@section('title', 'Subscriptions Control')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-800 m-0 text-dark">ACCESS_PROTOCOL</h4>
    <button class="btn btn-primary rounded-pill px-4 py-2 fw-900 shadow-lg no-print" data-bs-toggle="modal" data-bs-target="#addSubModal">
        <i class="bi bi-plus-circle-fill me-1"></i> ACTIVATE
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="stat-card" style="border-left: 4px solid var(--admin-accent);">
            <div class="stat-label">Yield to Date</div>
            <div class="stat-val">{{ number_format($totalRev) }} <small class="fs-6 text-primary">RWF</small></div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-label">Connected Nodes</div>
            <div class="stat-val text-success">{{ $activeNow }} <small class="fs-6 opacity-50">Active</small></div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-label">System Drift</div>
            <div class="stat-val text-danger">{{ $subs->count() - $activeNow }} <small class="fs-6 opacity-50">Expired</small></div>
        </div>
    </div>
</div>

<div class="card-pro">
    <h6 class="fw-900 text-info mb-4 text-uppercase">Network Authorization Log</h6>

    <div class="table-responsive desktop-table">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Authorized Node</th>
                    <th>Deployment Tier</th>
                    <th>Validity Cycle</th>
                    <th>Status</th>
                    <th class="text-end no-print">Command</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subs as $s) 
                @php
                    $isAct = ($s->end_date > now() && $s->status === 'active');
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold shadow-sm flex-shrink-0" style="width: 38px; height: 38px; font-size: 1.1rem;">
                                {{ strtoupper(substr($s->shop_name ?: $s->full_name, 0, 1)) }}
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark text-truncate" style="max-width: 150px; font-size: 0.9rem;">{{ $s->shop_name ?: $s->full_name }}</span>
                                <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-envelope me-1"></i> {{ $s->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column align-items-start">
                            <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1 fw-bold mb-1" style="font-size: 0.65rem;">{{ strtoupper($s->plan_name) }}</span>
                            <span class="fw-900 text-dark">{{ number_format($s->amount) }} <span class="fs-6 fw-normal text-muted">RWF</span></span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-muted fw-medium" style="font-size: 0.75rem;"><i class="bi bi-calendar-check me-1"></i>{{ date('M d, Y', strtotime($s->start_date)) }}</span>
                            <span class="fw-bold text-dark" style="font-size: 0.85rem;"><i class="bi bi-arrow-right-short text-muted"></i> {{ date('M d, Y', strtotime($s->end_date)) }}</span>
                        </div>
                    </td>
                    <td><span class="status-badge {{ $isAct ? 'st-active' : 'st-expired' }}">{{ $isAct ? 'CONNECTED' : 'EXPIRED' }}</span></td>
                    <td class="text-end no-print">
                        @if($isAct)
                        <form method="POST" action="{{ route('admin.subscriptions.revoke') }}" class="d-inline" onsubmit="return confirm('Revoke access?');">
                            @csrf
                            <input type="hidden" name="sub_id" value="{{ $s->id }}">
                            <button type="submit" class="btn btn-sm btn-white border shadow-sm rounded-pill px-3 fw-bold text-danger"><i class="bi bi-x-circle me-1"></i> Revoke</button>
                        </form>
                        @else
                        <span class="text-muted small">None</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mobile-list">
        @foreach ($subs as $s) 
        @php
            $isAct = ($s->end_date > now() && $s->status === 'active');
        @endphp
        <div class="mobile-sub-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="fw-900 text-primary small">{{ $s->shop_name ?: $s->full_name }}</div>
                    <div class="badge bg-indigo-subtle text-primary fw-bold" style="font-size: 0.6rem; Hamburg: 5px;">{{ strtoupper($s->plan_name) }}</div>
                </div>
                <span class="status-badge {{ $isAct ? 'st-active' : 'st-expired' }}">{{ $isAct ? 'LIVE' : 'DOWN' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="small fw-bold text-muted">ENDS: {{ date('d/m/y', strtotime($s->end_date)) }}</div>
                @if($isAct)
                <form method="POST" action="{{ route('admin.subscriptions.revoke') }}">
                    @csrf
                    <input type="hidden" name="sub_id" value="{{ $s->id }}">
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">REVOKE</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Subscription Modal -->
<div class="modal fade" id="addSubModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-900 text-dark">NODE_AUTHORIZATION</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form method="POST" action="{{ route('admin.subscriptions.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="small fw-800 text-info mb-2">TARGET VENDOR</label>
                        <select name="user_id" class="form-select fw-bold" required>
                            <option value="">Select Instance...</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}">{{ htmlspecialchars($v->shop_name ?: $v->full_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="small fw-800 text-info mb-2">PLAN TIER</label>
                            <select name="plan_type" class="form-select fw-bold">
                                <option value="2000">Standard (2K)</option>
                                <option value="5000">Pro (5K)</option>
                                <option value="10000">Ent (10K)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-800 text-info mb-2">DURATION</label>
                            <select name="duration" class="form-select fw-bold">
                                <option value="1">1 Month</option>
                                <option value="6">6 Months</option>
                                <option value="12">1 Year</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-900 shadow-lg">PUSH AUTHORIZATION</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stat-card { background: var(--admin-card); border-radius: 24px; padding: 1.5rem; border: none; box-shadow: var(--shadow-sm); height: 100%; transition: all 0.3s ease; }
    .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-5px); }
    .stat-label { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; }
    .stat-val { font-size: 1.5rem; font-weight: 900; margin-top: 0.5rem; }

    .card-pro { background: var(--admin-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.75rem; box-shadow: var(--shadow-sm); transition: all 0.3s ease; }
    .card-pro:hover { box-shadow: var(--shadow-md); border-color: #cbd5e1; }
    
    .table-custom { color: var(--admin-text); vertical-align: middle; border-collapse: separate; border-spacing: 0 0.5rem; white-space: nowrap; }
    .table-custom thead th { border: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--admin-muted); padding: 1rem; font-weight: 800; }
    .table-custom tbody tr { background-color: #f8fafc; transition: all 0.2s ease; border-radius: 16px; }
    .table-custom tbody tr:hover { background-color: white; box-shadow: var(--shadow-md); }
    .table-custom td { padding: 1rem; border: none; border-top: 1px solid transparent; border-bottom: 1px solid transparent; }
    .table-custom td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; border-left: 1px solid transparent; }
    .table-custom td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; border-right: 1px solid transparent; }
    .table-custom tbody tr:hover td { border-color: var(--border); }
    
    .status-badge { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; padding: 6px 12px; border-radius: 50px; }
    .st-active { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .st-expired { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

    .modal-content { background: #ffffff; border-radius: 28px; border: 1px solid var(--border); color: var(--admin-text); }
    .form-select, .form-control { background: #f1f5f9; color: var(--admin-text); border: 1px solid var(--border); border-radius: 12px; padding: 0.75rem; }
    .form-control:focus, .form-select:focus { background: #ffffff; border-color: var(--admin-accent); color: var(--admin-text); box-shadow: none; }

    @media (max-width: 768px) {
        .desktop-table { display: none; }
        .mobile-sub-card { background: var(--admin-card); border: 1px solid var(--border); border-radius: 20px; padding: 1.25rem; margin-bottom: 1rem; box-shadow: var(--shadow-sm); }
        .stat-val { font-size: 1.25rem; }
    }
    @media (min-width: 769px) { .mobile-list { display: none; } }
</style>
@endsection
