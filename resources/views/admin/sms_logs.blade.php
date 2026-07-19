@extends('layouts.admin')

@section('title', 'SMS History Logs')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-800 m-0">SMS_GATEWAY</h4>
    <button class="btn btn-primary rounded-pill px-4 py-2 fw-900 shadow-lg no-print" data-bs-toggle="modal" data-bs-target="#broadcastSmsModal">
        <i class="bi bi-broadcast me-1"></i> BROADCAST VENDORS
    </button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-card" style="border-left: 4px solid var(--admin-accent);">
            <div class="stat-label">Total Outbound SMS</div>
            <div class="stat-val text-dark">{{ $logs->count() }} <small class="fs-6 text-muted">Sent</small></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card" style="border-left: 4px solid #ef4444;">
            <div class="stat-label">System Failures</div>
            <div class="stat-val text-danger">{{ $failCount }} <small class="fs-6 text-muted">Bounced</small></div>
        </div>
    </div>
</div>

<div class="card-pro">
    <h6 class="fw-900 text-info mb-4 text-uppercase">Outbound Logs Registry</h6>

    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Message Details</th>
                    <th>Date Dispatch</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if($logs->isEmpty())
                    <tr><td colspan="4" class="text-center py-5 opacity-50 small fw-bold">NO LOGS RECORDED</td></tr>
                @else
                    @foreach ($logs as $log)
                    <tr>
                        <td>
                            <div class="fw-900 text-dark">{{ $log->recipient }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $log->vendor->shop_name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <p class="mb-1 text-dark small" style="white-space: pre-line; max-width: 350px;">{{ $log->message_body }}</p>
                            <small class="text-muted" style="font-size: 0.65rem;">Gateway: {{ $log->gateway_response }}</small>
                        </td>
                        <td><span class="text-muted small fw-medium">{{ date('d M, Y • H:i', strtotime($log->created_at)) }}</span></td>
                        <td>
                            @php $isSent = ($log->status === 'sent'); @endphp
                            <span class="status-badge {{ $isSent ? 'st-active' : 'st-expired' }}">{{ strtoupper($log->status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Broadcast SMS Modal -->
<div class="modal fade" id="broadcastSmsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-900 text-dark">BROADCAST_SMS_VENDORS</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form method="POST" action="{{ route('admin.sms_logs.broadcast') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="small fw-800 text-info mb-2">BROADCAST MESSAGE</label>
                        <textarea name="broadcast_message" class="form-control" rows="5" placeholder="Write message to all merchants here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-900 shadow-lg">DISPATCH BROADCAST</button>
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
    
    .table-custom { color: var(--admin-text); vertical-align: middle; border-collapse: separate; border-spacing: 0 0.5rem; }
    .table-custom thead th { border: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--admin-muted); padding: 1rem; font-weight: 800; }
    .table-custom tbody tr { background-color: #f8fafc; transition: all 0.2s ease; border-radius: 16px; }
    .table-custom tbody tr:hover { background-color: white; box-shadow: var(--shadow-sm); }
    .table-custom td { padding: 1rem; border: none; border-top: 1px solid transparent; border-bottom: 1px solid transparent; }
    .table-custom td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; border-left: 1px solid transparent; }
    .table-custom td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; border-right: 1px solid transparent; }
    .table-custom tbody tr:hover td { border-color: var(--border); }
    
    .status-badge { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; padding: 6px 12px; border-radius: 50px; }
    .st-active { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .st-expired { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

    .modal-content { background: #ffffff; border-radius: 28px; border: 1px solid var(--border); color: var(--admin-text); }
    .form-control { background: #f1f5f9; color: var(--admin-text); border: 1px solid var(--border); border-radius: 12px; padding: 0.75rem; }
    .form-control:focus { background: #ffffff; border-color: var(--admin-accent); color: var(--admin-text); box-shadow: none; }
</style>
@endsection
