@extends('layouts.admin')

@section('title', 'Ad Operations')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-800 m-0">AD_OPERATIONS</h4>
    <button class="btn btn-primary rounded-pill px-4 py-2 fw-900" data-bs-toggle="modal" data-bs-target="#pushAdModal">
        <i class="bi bi-lightning-charge-fill me-1 text-white"></i> <span class="text-white">DEPLOY</span>
    </button>
</div>

<div class="card-pro">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-900 m-0 text-dark">LEAD PIPELINE</h6>
        <span class="badge bg-indigo-subtle text-primary fw-800 rounded-pill">{{ $inquiries->count() }} ACTIVE</span>
    </div>

    <div class="table-responsive desktop-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Campaign</th>
                    <th>Phase</th>
                    <th class="text-end">Command</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inquiries as $row)
                <tr>
                    <td>
                        <div class="fw-900 small text-dark">{{ $row->business }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $row->name }}</div>
                    </td>
                    <td><span class="badge bg-light text-dark fw-900" style="font-size: 0.6rem;">{{ strtoupper($row->package) }}</span></td>
                    <td>
                        @php $statusClass = (strtolower($row->status) == 'paid') ? 'st-paid' : 'st-new'; @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $row->status }}</span>
                    </td>
                    <td class="text-end">
                        <button class="btn-action me-2 border-0" onclick="showMsg('{{ htmlspecialchars($row->business) }}', '{{ addslashes($row->message) }}')"><i class="bi bi-chat-dots-fill text-primary"></i></button>
                        <form method="POST" action="{{ route('admin.ads.inquiry_status') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="inquiry_id" value="{{ $row->id }}"><input type="hidden" name="status" value="paid">
                            <button type="submit" class="btn-action border-0" style="background:rgba(16, 185, 129, 0.1);"><i class="bi bi-check-lg text-success"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mobile-list">
        @foreach ($inquiries as $row)
        <div class="mobile-inquiry-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="fw-900 text-primary">{{ $row->business }}</div>
                    <div class="text-muted small">{{ $row->name }}</div>
                </div>
                @php $statusClass = (strtolower($row->status) == 'paid') ? 'st-paid' : 'st-new'; @endphp
                <span class="status-badge {{ $statusClass }}">{{ $row->status }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-light text-dark border border-dark border-opacity-10 fw-900" style="font-size: 0.6rem;">{{ strtoupper($row->package) }}</span>
                <div class="d-flex gap-2">
                    <button class="btn-action border-0" onclick="showMsg('{{ htmlspecialchars($row->business) }}', '{{ addslashes($row->message) }}')"><i class="bi bi-chat-dots-fill text-primary"></i></button>
                    <form method="POST" action="{{ route('admin.ads.inquiry_status') }}">
                        @csrf
                        <input type="hidden" name="inquiry_id" value="{{ $row->id }}"><input type="hidden" name="status" value="paid">
                        <button type="submit" class="btn-action border-0" style="background:rgba(16, 185, 129, 0.1);"><i class="bi bi-check-lg text-success"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Push Ad Modal -->
<div class="modal fade" id="pushAdModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-900 text-dark">DEPLOY_CAMPAIGN</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form method="POST" action="{{ route('admin.ads.deploy') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="small fw-800 text-info mb-2">TITLE</label>
                        <input type="text" name="ad_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-800 text-info mb-2">URL</label>
                        <input type="url" name="ad_link" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="small fw-800 text-info mb-2">FORMAT</label>
                        <select name="ad_type" class="form-select">
                            <option value="banner">Banner</option>
                            <option value="popup">Popup</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-900 shadow-lg">PUSH LIVE</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showMsg(biz, msg) {
        Swal.fire({
            title: biz, text: msg, background: '#ffffff', color: '#1e293b',
            confirmButtonText: 'ACKNOWLEDGE',
            customClass: { confirmButton: 'btn btn-primary rounded-pill px-4 fw-bold' },
            buttonsStyling: false
        });
    }
</script>
@endsection

@section('styles')
<style>
    .card-pro { background: var(--adm-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; position: relative; overflow: hidden; }
    
    .table { color: var(--adm-text); vertical-align: middle; }
    .table thead th { color: var(--adm-sidebar-accent); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 1px solid var(--border); padding: 1rem; }
    
    .status-badge { font-size: 0.65rem; font-weight: 800; padding: 6px 12px; border-radius: 50px; text-transform: uppercase; }
    .st-new { background: rgba(99, 102, 241, 0.2); color: #6366f1; }
    .st-paid { background: rgba(16, 185, 129, 0.2); color: #10b981; }

    .btn-action { width: 44px; height: 44px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: var(--adm-text); border: 1px solid var(--border); }
    
    .modal-content { background: #ffffff; border-radius: 28px; border: 1px solid var(--border); color: var(--adm-text); }
    .form-control, .form-select { border-radius: 12px; padding: 0.8rem; border: 1px solid var(--border); background: #f1f5f9; color: var(--adm-text); }

    @media (max-width: 768px) {
        .desktop-table { display: none; }
        .mobile-inquiry-card { background: var(--adm-card); border: 1px solid var(--border); border-radius: 20px; padding: 1.25rem; margin-bottom: 1rem; }
    }
    @media (min-width: 769px) { .mobile-list { display: none; } }
</style>
@endsection
