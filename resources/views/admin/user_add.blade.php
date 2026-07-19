@extends('layouts.admin')

@section('title', 'Provision Node')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-800 m-0">PROVISION_NODE</h4>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary rounded-pill px-3 py-1 fw-bold small">BACK</a>
</div>

@if ($errors->any())
    <div class="alert bg-danger text-white border-0 rounded-4 mb-4 small fw-bold">
        <i class="bi bi-exclamation-octagon-fill me-2"></i>
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            <div class="preview-card sticky-top" style="top: 100px;">
                <div class="avatar-preview" id="avatarPreview">U</div>
                <h6 class="fw-900 text-dark mb-1 text-truncate" id="namePreview">NEW_ENTITY</h6>
                <p class="text-muted small mb-3 text-truncate" id="emailPreview">LISTENING...</p>
                <span class="badge bg-indigo-subtle text-primary border border-primary border-opacity-20 rounded-pill px-3 py-2 fw-800" style="font-size: 0.6rem;" id="roleText">CUSTOMER</span>
            </div>
        </div>

        <div class="col-lg-8 col-xl-9">
            <div class="card-pro mb-4">
                <h6 class="fw-900 text-info mb-4">IDENTITY_CORE</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Full Legal Name</label>
                        <input type="text" name="full_name" class="form-control" id="inputName" placeholder="e.g. John Doe" onkeyup="updatePreview()" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Network Email</label>
                        <input type="email" name="email" class="form-control" id="inputEmail" placeholder="john@imanzi.link" onkeyup="updatePreview()" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Phone Node</label>
                        <input type="text" name="phone" class="form-control" placeholder="078XXXXXXX" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Access Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control border-end-0" id="inputPass" value="Kura@2026" required>
                            <button class="btn btn-dark border border-start-0 border-dark border-opacity-10 fw-bold px-3" style="font-size: 0.7rem;" type="button" onclick="genPass()">GENERATE</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-pro mb-4">
                <h6 class="fw-900 text-info mb-4">ACCESS_TIER</h6>
                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <input type="radio" class="btn-check" name="role" id="rCust" value="customer" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }} onchange="setRole('CUSTOMER')">
                        <label class="role-card" for="rCust"><i class="bi bi-person fs-4 d-block"></i><span class="small fw-900">CLIENT</span></label>
                    </div>
                    <div class="col-4">
                        <input type="radio" class="btn-check" name="role" id="rVend" value="vendor" {{ old('role') === 'vendor' ? 'checked' : '' }} onchange="setRole('VENDOR')">
                        <label class="role-card" for="rVend"><i class="bi bi-shop fs-4 d-block"></i><span class="small fw-900">MERCHANT</span></label>
                    </div>
                    <div class="col-4">
                        <input type="radio" class="btn-check" name="role" id="rAdm" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }} onchange="setRole('ADMIN')">
                        <label class="role-card" for="rAdm"><i class="bi bi-shield-lock fs-4 d-block"></i><span class="small fw-900">SUPER</span></label>
                    </div>
                </div>

                <div id="shopField" class="{{ old('role') === 'vendor' ? '' : 'd-none' }} animate__animated animate__fadeIn mb-4">
                    <label class="form-label text-warning">Brand Identity (Shop Name)</label>
                    <input type="text" name="shop_name" class="form-control border-warning border-opacity-25" id="inputShop" placeholder="e.g. Imanzi Kigali Hub" value="{{ old('shop_name') }}">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-900 shadow-lg">INITIALIZE DEPLOYMENT</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    function updatePreview() {
        const n = document.getElementById('inputName').value;
        const e = document.getElementById('inputEmail').value;
        document.getElementById('namePreview').innerText = n || 'NEW_ENTITY';
        document.getElementById('emailPreview').innerText = e || 'LISTENING...';
        document.getElementById('avatarPreview').innerText = n ? n.charAt(0).toUpperCase() : 'U';
    }
    function setRole(r) {
        document.getElementById('roleText').innerText = r;
        const s = document.getElementById('shopField');
        r === 'VENDOR' ? s.classList.remove('d-none') : s.classList.add('d-none');
    }
    function genPass() {
        const chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%";
        let p = ""; for (let i = 0; i < 12; i++) p += chars.charAt(Math.floor(Math.random() * chars.length));
        const el = document.getElementById('inputPass'); el.value = p; el.type = "text";
        setTimeout(() => el.type = "password", 3000);
    }
    // Initialize preview on page load
    document.addEventListener('DOMContentLoaded', () => {
        updatePreview();
        const activeRole = document.querySelector('input[name="role"]:checked').value.toUpperCase();
        setRole(activeRole);
    });
</script>
@endsection

@section('styles')
<style>
    .preview-card { background: var(--admin-card); border-radius: 24px; padding: 2rem; border: 1px solid var(--border); box-shadow: 0 10px 20px rgba(0,0,0,0.05); text-align: center; }
    .avatar-preview { width: 80px; height: 80px; background: var(--admin-accent); color: white; font-size: 2rem; font-weight: 800; display: flex; align-items: center; justify-content: center; border-radius: 20px; margin: 0 auto 1rem; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); border: 1px solid var(--border); }

    .card-pro { background: var(--admin-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; }
    .form-label { font-size: 0.65rem; font-weight: 800; color: var(--admin-accent); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
    .form-control, .form-select { background: #f1f5f9; color: var(--admin-text); border: 1px solid var(--border); border-radius: 12px; padding: 0.8rem; }
    .form-control:focus { background: #ffffff; border-color: var(--admin-accent); color: var(--admin-text); box-shadow: none; }

    .role-card { border: 1px solid var(--border); border-radius: 18px; padding: 1.25rem; cursor: pointer; transition: 0.3s; background: #f1f5f9; text-align: center; height: 100%; }
    .btn-check:checked + .role-card { background: var(--admin-accent); border-color: var(--admin-accent); color: white; box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3); }

    @media (max-width: 768px) {
        .preview-card { margin-bottom: 1.5rem; padding: 1.5rem; }
        .preview-card .avatar-preview { width: 60px; height: 60px; font-size: 1.5rem; }
        .form-control { font-size: 0.9rem; }
    }
</style>
@endsection
