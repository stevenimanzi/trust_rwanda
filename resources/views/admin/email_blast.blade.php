@extends('layouts.admin')

@section('title', 'Blast Center')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-800 m-0">BLAST_CENTER</h4>
    <span class="badge bg-indigo-subtle text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 fw-800">SMTP Handshake Mode</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-pro">
            <h6 class="fw-900 text-info mb-4">NEW CAMPAIGN PARAMETERS</h6>
            <form method="POST" action="{{ route('admin.email_blast.send') }}" id="blastForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="e.g. System Protocol Update" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Segment Target</label>
                    <select name="target_audience" class="form-select fw-bold">
                        <option value="all">Global Matrix Cluster (Customers + Subscribers) [{{ $userStats['all'] }}]</option>
                        <option value="customer">Registered Clients Only [{{ $userStats['customer'] }}]</option>
                        <option value="vendor">Merchants Network [{{ $userStats['vendor'] }}]</option>
                        <option value="subscribers">Newsletter Subscriptions [{{ $userStats['subs'] }}]</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Email Body (HTML supported)</label>
                    <textarea name="email_body" id="email_body" class="form-control" rows="12" placeholder="Write message here... Use {name} for personalization." required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-900 shadow-lg">DISPATCH BLAST SEQUENCE</button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-pro mb-4">
            <h6 class="fw-900 text-info mb-4">PERSONALIZATION STRINGS</h6>
            <div class="p-3 bg-light rounded-4 border mb-3">
                <code class="fw-bold fs-6 text-primary">{name}</code>
                <p class="text-muted small mb-0 mt-1">Replaced with the recipient's full legal name.</p>
            </div>
            <div class="p-3 bg-light rounded-4 border">
                <code class="fw-bold fs-6 text-primary">{shop}</code>
                <p class="text-muted small mb-0 mt-1">Replaced with the vendor shop name (vendors only).</p>
            </div>
        </div>

        <div class="card-pro text-center p-5 bg-indigo-subtle bg-opacity-10 border border-primary border-opacity-15">
            <i class="bi bi-shield-fill-check text-primary display-3 mb-3"></i>
            <h6 class="fw-900 text-dark">Spam Protection Filter</h6>
            <p class="text-muted small mb-0">System automatically inserts anti-spam headers and respects unsubscribe tokens.</p>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-pro { background: var(--adm-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.75rem; box-shadow: var(--shadow-sm); }
    .form-label { font-size: 0.65rem; font-weight: 800; color: var(--admin-accent); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
    .form-control, .form-select { background: #f1f5f9; color: var(--admin-text); border: 1px solid var(--border); border-radius: 12px; padding: 0.8rem; }
    .form-control:focus, .form-select:focus { background: #ffffff; border-color: var(--admin-accent); color: var(--admin-text); box-shadow: none; }
</style>
@endsection
