@extends('layouts.admin')

@section('title', 'Human Capital')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-800 m-0 text-dark">USER_MANAGEMENT</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-3 py-2 fw-900 small shadow-lg">
        <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline ms-1">NEW NODE</span>
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card bg-grad-primary">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-val">{{ $totalUsers }}</div>
                    <div class="kpi-label">Total Users</div>
                </div>
                <div class="kpi-icon"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card bg-grad-success">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-val">{{ $verifiedCount }}</div>
                    <div class="kpi-label">Verified Nodes</div>
                </div>
                <div class="kpi-icon"><i class="bi bi-shield-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card bg-grad-warning">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-val">{{ $totalVendors }}</div>
                    <div class="kpi-label">Merchants</div>
                </div>
                <div class="kpi-icon"><i class="bi bi-shop"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card bg-grad-info">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-val">{{ $totalCustomers }}</div>
                    <div class="kpi-label">Clients</div>
                </div>
                <div class="kpi-icon"><i class="bi bi-person-heart"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="filter-container">
    <div class="row g-3 align-items-center">
        <div class="col-lg-6">
            <div class="nav nav-pills overflow-auto flex-nowrap">
                <a class="nav-link {{ $filterRole == 'all' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'all', 'q' => $search]) }}">MASTER</a>
                <a class="nav-link {{ $filterRole == 'vendor' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'vendor', 'q' => $search]) }}">MERCHANTS</a>
                <a class="nav-link {{ $filterRole == 'customer' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'customer', 'q' => $search]) }}">CLIENTS</a>
            </div>
        </div>
        <div class="col-lg-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="input-group">
                <input type="hidden" name="role" value="{{ $filterRole }}">
                <input type="text" name="q" class="form-control ps-3" placeholder="Search Matrix..." value="{{ $search }}">
                <button class="btn btn-primary fw-900 px-3"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>
</div>

<div class="card-pro">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="card-header-title"><i class="bi bi-person-lines-fill"></i> User Directory</div>
    </div>
    
    <div class="table-responsive desktop-table">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Identity</th>
                    <th>Tier</th>
                    <th>Context</th>
                    <th class="text-end">Command</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u) 
                @php 
                    $avatarBg = ['#6366f1', '#ec4899', '#10b981', '#f59e0b'][$u->id % 4];
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-box text-white" style="background: {{ $avatarBg }}">{{ strtoupper(substr($u->full_name, 0, 1)) }}</div>
                            <div>
                                <div class="fw-800 small">{{ $u->full_name }}</div>
                                <div class="text-muted" style="font-size:0.65rem;">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-indigo-subtle text-primary border border-primary border-opacity-10 rounded-pill px-3 py-1 fw-900 small uppercase">{{ $u->role }}</span></td>
                    <td>
                        @if($u->role === 'vendor')
                            <div class="fw-800 text-info small">{{ $u->shop_name ?: 'UNSET' }}</div>
                        @endif
                        <span class="status-badge {{ $u->is_verified ? 'st-active' : 'st-restricted' }} mt-1">{{ $u->is_verified ? 'CONNECTED' : 'DOWN' }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn-action border-0" onclick='openEditModal({!! json_encode($u) !!})'><i class="bi bi-pencil-square"></i></button>
                            <form method="POST" action="{{ route('admin.users.toggle') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $u->id }}">
                                <input type="hidden" name="new_status" value="{{ $u->is_verified ? '0' : '1' }}">
                                <button class="btn-action border-0"><i class="bi {{ $u->is_verified ? 'bi-lock' : 'bi-unlock' }}"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mobile-list">
        @foreach ($users as $u)
        <div class="mobile-user-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-box text-white" style="background: var(--admin-accent); width: 40px; height: 40px;">{{ strtoupper(substr($u->full_name, 0, 1)) }}</div>
                    <div>
                        <div class="fw-900 small text-primary">{{ $u->full_name }}</div>
                        <div class="text-muted" style="font-size:0.65rem;">{{ $u->email }}</div>
                    </div>
                </div>
                <span class="status-badge {{ $u->is_verified ? 'st-active' : 'st-restricted' }}">{{ $u->is_verified ? 'CONNECTED' : 'DOWN' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center border-top border-dark border-opacity-10 pt-3">
                <span class="badge bg-indigo-subtle text-primary fw-bold" style="font-size: 0.6rem;">{{ strtoupper($u->role) }}</span>
                <div class="d-flex gap-2">
                    <button class="btn-action border-0" onclick='openEditModal({!! json_encode($u) !!})'><i class="bi bi-pencil"></i></button>
                    <form method="POST" action="{{ route('admin.users.delete') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $u->id }}">
                        <button class="btn-action text-danger border-0" onclick="return confirm('Purge node?')"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h6 class="fw-900 text-dark">MODIFY_NODE_ACCESS</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form method="POST" action="{{ route('admin.users.edit') }}">
                    @csrf
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="mb-3">
                        <label class="small fw-800 text-info mb-2 uppercase">Identity Name</label>
                        <input type="text" name="full_name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-800 text-info mb-2 uppercase">Network Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="small fw-800 text-info mb-2 uppercase">Tier</label>
                            <select name="role" id="edit_role" class="form-select fw-bold" onchange="toggleEditShop()">
                                <option value="customer">Client</option>
                                <option value="vendor">Merchant</option>
                                <option value="admin">Superuser</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-800 text-info mb-2 uppercase">New Key</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Optional">
                        </div>
                    </div>
                    <div class="mb-4 d-none" id="edit_shop_container">
                        <label class="small fw-800 text-warning mb-2">MERCHANT_BRAND</label>
                        <input type="text" name="shop_name" id="edit_shop" class="form-control border-warning border-opacity-25">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-900 shadow-lg">PUSH SYNC PROTOCOL</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditModal(u) {
        document.getElementById('edit_user_id').value = u.id;
        document.getElementById('edit_name').value = u.full_name;
        document.getElementById('edit_email').value = u.email;
        document.getElementById('edit_role').value = u.role;
        document.getElementById('edit_shop').value = u.shop_name || '';
        toggleEditShop();
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    }
    function toggleEditShop() {
        const c = document.getElementById('edit_shop_container');
        document.getElementById('edit_role').value === 'vendor' ? c.classList.remove('d-none') : c.classList.add('d-none');
    }
</script>
@endsection

@section('styles')
<style>
    .filter-container { background: var(--admin-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-sm); }
    .nav-pills .nav-link { color: #94a3b8; font-weight: 800; border-radius: 12px; padding: 0.5rem 1.2rem; font-size: 0.75rem; border: 1px solid transparent; }
    .nav-pills .nav-link.active { background: var(--admin-accent); color: white; box-shadow: 0 8px 15px rgba(79, 70, 229, 0.3); }

    .kpi-card { border-radius: 24px; padding: 1.5rem; border: none; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: var(--shadow-sm); position: relative; overflow: hidden; z-index: 1; height: 100%; }
    .kpi-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .kpi-card::before { content: ''; position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.15); z-index: -1; transition: all 0.4s ease; }
    .kpi-card:hover::before { transform: scale(1.2); }
    
    .kpi-icon { width: 56px; height: 56px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; background: rgba(255, 255, 255, 0.25); color: white; margin-bottom: 1rem; box-shadow: var(--shadow-sm); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.3); }
    .kpi-val { font-size: 2rem; font-weight: 900; letter-spacing: -0.5px; line-height: 1.1; margin-bottom: 0.25rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1); color: white; }
    .kpi-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; color: white; }

    .bg-grad-primary { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color: white; }
    .bg-grad-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .bg-grad-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .bg-grad-info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; }

    .card-pro { background: var(--admin-card); border-radius: 24px; border: 1px solid var(--border); padding: 1.75rem; box-shadow: var(--shadow-sm); transition: all 0.3s ease; }
    .card-pro:hover { box-shadow: var(--shadow-md); border-color: #cbd5e1; }
    
    .card-header-title { font-weight: 800; color: var(--admin-text); font-size: 1.1rem; display: flex; align-items: center; gap: 0.6rem; }
    .card-header-title i { color: var(--admin-accent); background: var(--admin-accent-light); padding: 0.4rem 0.5rem; border-radius: 10px; font-size: 1.1rem; }

    .table-custom { color: var(--admin-text); vertical-align: middle; border-collapse: separate; border-spacing: 0 0.5rem; white-space: nowrap; }
    .table-custom thead th { border: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--admin-muted); padding: 1rem; font-weight: 800; }
    .table-custom tbody tr { background-color: #f8fafc; transition: all 0.2s ease; border-radius: 16px; }
    .table-custom tbody tr:hover { background-color: white; box-shadow: var(--shadow-md); }
    .table-custom td { padding: 1rem; border: none; border-top: 1px solid transparent; border-bottom: 1px solid transparent; }
    .table-custom td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; border-left: 1px solid transparent; }
    .table-custom td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; border-right: 1px solid transparent; }
    .table-custom tbody tr:hover td { border-color: var(--border); }

    .avatar-box { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    .status-badge { font-size: 0.6rem; font-weight: 800; padding: 5px 12px; border-radius: 50px; text-transform: uppercase; }
    .st-active { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .st-restricted { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

    .btn-action { width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: var(--admin-text); border: 1px solid var(--border); transition: 0.3s; }
    .btn-action:hover { background: var(--admin-accent); color: white; transform: translateY(-2px); border-color: var(--admin-accent); }

    .modal-content { background: #ffffff; border-radius: 28px; border: 1px solid var(--border); color: var(--admin-text); }
    .form-control, .form-select { background: #f1f5f9; color: var(--admin-text); border: 1px solid var(--border); border-radius: 12px; padding: 0.75rem; }
    .form-control:focus, .form-select:focus { background: #ffffff; border-color: var(--admin-accent); color: var(--admin-text); box-shadow: none; }

    @media (max-width: 768px) {
        .desktop-table { display: none; }
        .mobile-user-card { background: #ffffff; border: 1px solid var(--border); border-radius: 20px; padding: 1.25rem; margin-bottom: 1rem; }
    }
    @media (min-width: 769px) { .mobile-list { display: none; } }
</style>
@endsection
