@extends('layouts.admin')

@section('title', 'Human Capital')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-800 m-0 text-dark">User Management</h4>
    <a href="{{ route('admin.users.create') }}" class="btn rounded-pill px-3 py-2 fw-900 small shadow-lg text-white" style="background: var(--hz-primary); border: none;">
        <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline ms-1">Add New User</span>
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div><div class="hz-card-subtitle">Total Users</div></div>
                <div class="hz-icon-btn shadow-sm" style="background: var(--hz-primary-light); color: var(--hz-primary);"><i class="bi bi-people"></i></div>
            </div>
            <div class="hz-kpi-value mb-2">{{ $totalUsers }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div><div class="hz-card-subtitle">Verified Users</div></div>
                <div class="hz-icon-btn shadow-sm" style="background: #dcfce7; color: #166534;"><i class="bi bi-shield-check"></i></div>
            </div>
            <div class="hz-kpi-value mb-2">{{ $verifiedCount }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div><div class="hz-card-subtitle">Merchants</div></div>
                <div class="hz-icon-btn shadow-sm" style="background: #fef3c7; color: #92400e;"><i class="bi bi-shop"></i></div>
            </div>
            <div class="hz-kpi-value mb-2">{{ $totalVendors }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="hz-card">
            <div class="hz-card-header">
                <div><div class="hz-card-subtitle">Clients</div></div>
                <div class="hz-icon-btn shadow-sm" style="background: #e0f2fe; color: #0284c7;"><i class="bi bi-person-heart"></i></div>
            </div>
            <div class="hz-kpi-value mb-2">{{ $totalCustomers }}</div>
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-lg-6">
            <div class="d-flex gap-2 overflow-auto pb-2 pb-lg-0">
                <a href="{{ route('admin.users.index', ['role' => 'all', 'q' => $search]) }}" class="btn rounded-pill fw-bold {{ $filterRole == 'all' ? 'btn-primary' : 'btn-light border-0' }} px-4">MASTER</a>
                <a href="{{ route('admin.users.index', ['role' => 'vendor', 'q' => $search]) }}" class="btn rounded-pill fw-bold {{ $filterRole == 'vendor' ? 'btn-primary' : 'btn-light border-0' }} px-4">MERCHANTS</a>
                <a href="{{ route('admin.users.index', ['role' => 'customer', 'q' => $search]) }}" class="btn rounded-pill fw-bold {{ $filterRole == 'customer' ? 'btn-primary' : 'btn-light border-0' }} px-4">CLIENTS</a>
            </div>
        </div>
        <div class="col-lg-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="input-group shadow-sm" style="border-radius: 50px; overflow: hidden;">
                <input type="hidden" name="role" value="{{ $filterRole }}">
                <span class="input-group-text bg-white border-0 text-muted ps-4"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control border-0 py-3" placeholder="Search users..." value="{{ $search }}" style="box-shadow: none;">
                <button type="submit" class="btn px-4 fw-bold text-white" style="background: var(--hz-primary); border: none;">Search</button>
            </form>
        </div>
    </div>
</div>

<div class="hz-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="hz-card-title"><i class="bi bi-person-lines-fill"></i> User Directory</div>
    </div>
    
    <div class="table-responsive">
        <table class="table hz-table align-middle">
            <thead>
                <tr>
                    <th>User Details</th>
                    <th>Role</th>
                    <th>Location / Shop</th>
                    <th class="text-end">Actions</th>
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
                            <div class="avatar-circle text-white fw-bold d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 12px; background: {{ $avatarBg }};">
                                {{ strtoupper(substr($u->full_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $u->full_name }}</div>
                                <div class="small text-muted">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($u->role == 'admin')
                            <span class="hz-badge hz-badge-danger">ADMIN</span>
                        @elseif($u->role == 'vendor')
                            <span class="hz-badge hz-badge-primary">VENDOR</span>
                        @else
                            <span class="hz-badge hz-badge-success">CUSTOMER</span>
                        @endif
                    </td>
                    <td>
                        @if($u->role == 'vendor')
                            <div class="small"><i class="bi bi-shop text-muted"></i> <strong>{{ $u->shop_name ?? 'N/A' }}</strong></div>
                        @else
                            <div class="small text-muted">-</div>
                        @endif
                        <div class="small text-muted mt-1"><i class="bi bi-geo-alt"></i> {{ $u->city ?? 'Unknown' }}</div>
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;">
                                <li><a class="dropdown-item" href="#" onclick='openEditModal({!! json_encode($u) !!})'><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Profile</a></li>
                                <li>
                                    <form method="POST" action="{{ route('admin.users.toggle') }}">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $u->id }}">
                                        <button class="dropdown-item" type="submit">
                                            @if($u->is_verified)
                                            <i class="bi bi-x-circle me-2 text-warning"></i> Suspend User
                                            @else
                                            <i class="bi bi-check-circle me-2 text-success"></i> Activate User
                                            @endif
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('admin.users.delete') }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be reversed.')">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $u->id }}">
                                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash3 me-2"></i> Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($users->isEmpty())
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        No entities found matching your criteria.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>

<div class="mobile-list">
    @foreach ($users as $u)
    <div class="mobile-user-card mb-3 p-3 bg-white rounded-4 border shadow-sm">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle shadow-sm" style="background: var(--hz-primary); width: 45px; height: 45px;">{{ strtoupper(substr($u->full_name, 0, 1)) }}</div>
                <div>
                    <div class="fw-bold text-dark">{{ $u->full_name }}</div>
                    <div class="text-muted" style="font-size:0.8rem;">{{ $u->email }}</div>
                </div>
            </div>
            @if($u->is_verified)
                <span class="hz-badge hz-badge-success">CONNECTED</span>
            @else
                <span class="hz-badge hz-badge-danger">DOWN</span>
            @endif
        </div>
        <div class="d-flex justify-content-between align-items-center border-top pt-3">
            <span class="badge bg-light text-dark fw-bold border">{{ strtoupper($u->role) }}</span>
            <div class="d-flex gap-2">
                <button class="btn btn-light btn-sm rounded-circle" onclick='openEditModal({!! json_encode($u) !!})'><i class="bi bi-pencil"></i></button>
                <form method="POST" action="{{ route('admin.users.delete') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $u->id }}">
                    <button class="btn btn-light btn-sm text-danger rounded-circle" onclick="return confirm('Purge node?')"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="offcanvas offcanvas-end shadow-lg border-0" tabindex="-1" id="editUserModal" style="width: 400px;">
    <div class="offcanvas-header border-bottom px-4 pt-4 pb-3">
        <h5 class="fw-900 text-dark m-0"><i class="bi bi-person-bounding-box text-primary me-2"></i> Edit User Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
        <!-- Tabs -->
        <ul class="nav nav-tabs hz-tabs border-bottom px-4 pt-3" id="userTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold pb-3" data-bs-toggle="tab" data-bs-target="#tab-account" type="button">Account</button>
            </li>
            <li class="nav-item d-none" id="tab-shop-nav" role="presentation">
                <button class="nav-link fw-bold pb-3" data-bs-toggle="tab" data-bs-target="#tab-shop" type="button">Shop Info</button>
            </li>
            <li class="nav-item d-none" id="tab-perf-nav" role="presentation">
                <button class="nav-link fw-bold pb-3" data-bs-toggle="tab" data-bs-target="#tab-perf" type="button">Performance</button>
            </li>
        </ul>
        
        <form method="POST" action="{{ route('admin.users.edit') }}" id="editUserForm" class="d-flex flex-column flex-grow-1 overflow-hidden">
            @csrf
            <input type="hidden" name="user_id" id="edit_user_id">
            
            <div class="tab-content flex-grow-1 overflow-auto p-4" id="userTabsContent">
                <!-- Account Tab -->
                <div class="tab-pane fade show active" id="tab-account" role="tabpanel">
                    <div class="mb-3">
                        <label class="small fw-800 text-muted mb-2 uppercase">Full Name</label>
                        <input type="text" name="full_name" id="edit_name" class="form-control bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-800 text-muted mb-2 uppercase">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="form-control bg-light" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="small fw-800 text-muted mb-2 uppercase">Role</label>
                            <select name="role" id="edit_role" class="form-select bg-light fw-bold" onchange="toggleEditShop()">
                                <option value="customer">Client</option>
                                <option value="vendor">Merchant</option>
                                <option value="admin">Superuser</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-800 text-muted mb-2 uppercase">New Password</label>
                            <input type="password" name="new_password" class="form-control bg-light" placeholder="Optional">
                        </div>
                    </div>
                </div>
                
                <!-- Shop Tab -->
                <div class="tab-pane fade" id="tab-shop" role="tabpanel">
                    <div class="mb-3">
                        <label class="small fw-800 text-muted mb-2 uppercase">Shop Name</label>
                        <input type="text" name="shop_name" id="edit_shop" class="form-control bg-light">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-800 text-muted mb-2 uppercase">Business Phone</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control bg-light">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-800 text-muted mb-2 uppercase">Shop Address</label>
                        <input type="text" name="address" id="edit_address" class="form-control bg-light">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-800 text-muted mb-2 uppercase">Shop Description</label>
                        <textarea name="shop_description" id="edit_description" class="form-control bg-light" rows="4"></textarea>
                    </div>
                </div>

                <!-- Performance Tab -->
                <div class="tab-pane fade" id="tab-perf" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="hz-card p-3 text-center h-100 shadow-sm border-0" style="background: var(--hz-primary-light);">
                                <i class="bi bi-wallet2 fs-3 mb-2" style="color: var(--hz-primary);"></i>
                                <div class="text-muted small fw-bold text-uppercase">Revenue</div>
                                <h6 class="fw-900 text-dark mb-0 mt-1" id="perf_revenue">0 RWF</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="hz-card p-3 text-center h-100 shadow-sm border-0 bg-light">
                                <i class="bi bi-box-seam text-success fs-3 mb-2"></i>
                                <div class="text-muted small fw-bold text-uppercase">Listings</div>
                                <h6 class="fw-900 text-dark mb-0 mt-1" id="perf_products">0</h6>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="hz-card p-3 text-center shadow-sm border-0 bg-light">
                                <i class="bi bi-truck text-warning fs-3 mb-2"></i>
                                <div class="text-muted small fw-bold text-uppercase">Total Fulfillments</div>
                                <h6 class="fw-900 text-dark mb-0 mt-1" id="perf_orders">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-top bg-white mt-auto">
                <button type="submit" class="btn w-100 py-3 rounded-pill fw-900 shadow-lg text-white" style="background: var(--hz-primary); border: none;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditModal(u) {
        // Reset tabs to first
        document.querySelector('#userTabs button[data-bs-target="#tab-account"]').click();
        
        document.getElementById('edit_user_id').value = u.id;
        document.getElementById('edit_name').value = u.full_name;
        document.getElementById('edit_email').value = u.email;
        document.getElementById('edit_role').value = u.role;
        document.getElementById('edit_shop').value = u.shop_name || '';
        document.getElementById('edit_phone').value = u.phone || '';
        document.getElementById('edit_address').value = u.address || '';
        document.getElementById('edit_description').value = u.shop_description || '';
        
        // Performance
        let revenue = u.vendor_revenue ? parseFloat(u.vendor_revenue) : 0;
        document.getElementById('perf_revenue').innerText = revenue.toLocaleString() + ' RWF';
        document.getElementById('perf_products').innerText = u.products_count || '0';
        document.getElementById('perf_orders').innerText = u.vendor_fulfillments || '0';
        
        toggleEditShop();
        new bootstrap.Offcanvas(document.getElementById('editUserModal')).show();
    }
    function toggleEditShop() {
        const isVendor = document.getElementById('edit_role').value === 'vendor';
        const shopNav = document.getElementById('tab-shop-nav');
        const perfNav = document.getElementById('tab-perf-nav');
        
        if (isVendor) {
            shopNav.classList.remove('d-none');
            perfNav.classList.remove('d-none');
        } else {
            shopNav.classList.add('d-none');
            perfNav.classList.add('d-none');
        }
    }
</script>
@endsection

@section('styles')
<style>
    .table-responsive { overflow: visible !important; }
    @media (max-width: 991px) {
        .table-responsive { display: none; }
    }
    @media (min-width: 992px) { 
        .mobile-list { display: none; } 
    }
</style>
@endsection
