@extends('layouts.app')

@section('title', 'My Profile - Trust Rwanda')

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white; padding: 3rem 0 5rem; margin-bottom: -3rem;
    }
    .profile-card {
        background: white; border-radius: 16px; border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;
    }
    .avatar-circle {
        width: 100px; height: 100px; border-radius: 50%; border: 4px solid white;
        background: #f1f5f9; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        display: flex; align-items: center; justify-content: center; font-size: 2.5rem;
    }
    
    .nav-pills .nav-link {
        color: #64748b; font-weight: 600; padding: 0.8rem 1.2rem; border-radius: 8px;
    }
    .nav-pills .nav-link.active {
        background-color: #eff6ff; color: #2563eb;
    }
    
    .status-badge { font-size: 0.75rem; font-weight: 700; padding: 0.35em 0.8em; border-radius: 50rem; text-transform: uppercase; }
    .status-pending { background: #fff7ed; color: #c2410c; }
    .status-delivered { background: #f0fdf4; color: #15803d; }
    .status-cancelled { background: #fef2f2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="profile-header text-center">
    <div class="container">
        <h2 class="fw-bold mb-1">My Account</h2>
        <p class="text-white-50">Manage your profile details and track commissions.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        
        <!-- Sidebar Column -->
        <div class="col-lg-4 mb-4">
            <div class="profile-card text-center p-4">
                <div class="d-flex justify-content-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=random&size=128" class="avatar-circle" alt="Avatar">
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->full_name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                    @if($user->role === 'vendor')
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">Vendor</span>
                    @elseif($user->role === 'real_estate_owner')
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-2">Property Owner</span>
                    @elseif($user->role === 'admin')
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">Administrator</span>
                    @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">Customer</span>
                    @endif
                </div>
            </div>
            
            <div class="profile-card p-4 mt-4 text-center">
                <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Spent</h6>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalSpent) }} RWF</h3>
            </div>
        </div>

        <!-- Details and Tabs Column -->
        <div class="col-lg-8">
            <div class="profile-card p-4">
                
                @if(session('status'))
                    <div class="alert alert-success border-0 bg-success-subtle text-success-emphasis rounded-3 mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger-emphasis rounded-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-orders-tab" data-bs-toggle="pill" data-bs-target="#pills-orders" type="button" role="tab">My Orders</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-affiliate-tab" data-bs-toggle="pill" data-bs-target="#pills-affiliate" type="button" role="tab">Affiliate Program</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-settings-tab" data-bs-toggle="pill" data-bs-target="#pills-settings" type="button" role="tab">Settings</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    
                    <!-- Orders Tab -->
                    <div class="tab-pane fade show active" id="pills-orders" role="tabpanel">
                        <h5 class="fw-bold mb-3">Recent Orders</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-3 py-3">Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($orders->isEmpty())
                                        <tr><td colspan="5" class="text-center py-5 text-muted">No orders found.</td></tr>
                                    @else
                                        @foreach($orders as $order)
                                        <tr>
                                            <td class="ps-3 fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="fw-bold text-dark">{{ number_format($order->total_amount) }} RWF</td>
                                            <td>
                                                @php
                                                    $status = $order->delivery_status ?? 'pending';
                                                    $cls = match($status) {
                                                        'delivered' => 'status-delivered',
                                                        'cancelled' => 'status-cancelled',
                                                        default => 'status-pending'
                                                    };
                                                @endphp
                                                <span class="status-badge {{ $cls }}">{{ ucfirst($status) }}</span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="#" class="btn btn-sm btn-light border">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Affiliate Tab -->
                    <div class="tab-pane fade" id="pills-affiliate" role="tabpanel">
                        <h5 class="fw-bold mb-3">Affiliate Dashboard</h5>
                        <p class="text-muted small mb-4">Refer clients to Trust Rwanda and earn up to 10% commission on every product purchased through your link!</p>
                        
                        <!-- Referral Link Generator -->
                        <div class="bg-light p-4 rounded-3 mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Your Unique Referral Link</label>
                            <div class="input-group">
                                <input type="text" id="refLinkInput" class="form-control bg-white" value="{{ $refLink }}" readonly>
                                <button class="btn btn-primary" type="button" onclick="copyRefLink()">
                                    <i class="bi bi-clipboard me-1"></i> Copy Link
                                </button>
                            </div>
                            <span class="text-success small mt-1 d-none" id="copySuccessMsg"><i class="bi bi-check2-circle"></i> Link copied to clipboard!</span>
                        </div>

                        <!-- Stats Row -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-white text-center">
                                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Referrals</span>
                                    <h4 class="fw-bold mb-0 text-dark">{{ $totalReferrals }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-white text-center">
                                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Pending</span>
                                    <h4 class="fw-bold mb-0 text-warning">{{ number_format($pendingComm) }} RWF</h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-white text-center">
                                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Approved</span>
                                    <h4 class="fw-bold mb-0 text-success">{{ number_format($approvedComm) }} RWF</h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-white text-center">
                                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Paid Out</span>
                                    <h4 class="fw-bold mb-0 text-primary">{{ number_format($paidComm) }} RWF</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Commissions Log -->
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Recent Commissions Log</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-3 py-3">Order</th>
                                        <th>Product</th>
                                        <th>Referred Client</th>
                                        <th>Commission (10%)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($commissionsList->isEmpty())
                                        <tr><td colspan="5" class="text-center py-4 text-muted">No referrals recorded yet. Share your link to start earning!</td></tr>
                                    @else
                                        @foreach($commissionsList as $comm)
                                        <tr>
                                            <td class="ps-3 fw-bold">#{{ str_pad($comm->order_id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="text-muted small">{{ $comm->product->title ?? 'N/A' }}</td>
                                            <td>{{ $comm->buyer->full_name ?? 'N/A' }}</td>
                                            <td class="fw-bold text-success">+{{ number_format($comm->commission_amount) }} RWF</td>
                                            <td>
                                                @php
                                                    $s = $comm->status ?? 'pending';
                                                    $cls = match($s) {
                                                        'approved' => 'bg-success-subtle text-success border-success-subtle',
                                                        'paid' => 'bg-primary-subtle text-primary border-primary-subtle',
                                                        default => 'bg-warning-subtle text-warning border-warning-subtle'
                                                    };
                                                @endphp
                                                <span class="badge border rounded-pill {{ $cls }}">{{ ucfirst($s) }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-pane fade" id="pills-settings" role="tabpanel">
                        <h5 class="fw-bold mb-4">Account Details</h5>
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->full_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Delivery Address</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="Street Number, City, District">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>
                            <div class="text-end mb-5">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Save Changes</button>
                            </div>
                        </form>

                        <h5 class="fw-bold mb-3 border-top pt-4">Security</h5>
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Min 6 characters" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm password" required>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button type="submit" class="btn btn-outline-dark rounded-pill w-100">Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyRefLink() {
    var copyText = document.getElementById("refLinkInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    var successMsg = document.getElementById("copySuccessMsg");
    if(successMsg) {
        successMsg.classList.remove("d-none");
        setTimeout(function() {
            successMsg.classList.add("d-none");
        }, 3000);
    }
}
</script>
@endsection
