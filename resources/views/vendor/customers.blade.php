@extends('layouts.vendor')

@section('title', 'My Customers')

@section('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 24px;
        border: none;
        height: 100%;
    }
    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--hz-text-main);
        margin-bottom: 20px;
    }
    
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
    
    .form-control-pro {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--hz-text-main);
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .form-control-pro:focus {
        border-color: var(--hz-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        background: #ffffff;
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f3e8ff;
        color: var(--hz-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="chart-title fs-4 mb-0">My Customers</div>
        <p class="text-muted m-0 small">Manage and view the users who have purchased from your store</p>
    </div>
</div>

<div class="ecom-card mb-4">
    <form action="{{ route('vendor.customers') }}" method="GET" class="d-flex gap-3 mb-4" id="searchForm">
        <div class="position-relative flex-grow-1" style="max-width: 400px;">
            <i class="bi bi-search position-absolute text-muted" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
            <input type="text" name="q" class="form-control-pro w-100 ps-5" placeholder="Search by name, email, or phone" value="{{ request('q') }}" id="searchInput">
        </div>
    </form>

    <div class="table-responsive">
        <table class="ecom-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contact Info</th>
                    <th class="text-center">Total Orders</th>
                    <th class="text-end">Total Spent</th>
                    <th class="text-end">Last Order</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($customers) && count($customers) > 0)
                    @foreach($customers as $c)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($c->full_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="fw-bold text-dark">{{ $c->full_name ?? 'Guest User' }}</div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $c->email ?? 'N/A' }}</div>
                            <div class="small text-muted"><i class="bi bi-telephone-fill me-1"></i> {{ $c->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $c->total_orders }}</span>
                        </td>
                        <td class="text-end fw-bold text-dark">RWF {{ number_format($c->total_spent) }}</td>
                        <td class="text-end text-muted small">
                            {{ $c->last_order_date ? date('j M Y', strtotime($c->last_order_date)) : 'N/A' }}
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="5" class="text-center py-5 text-muted">No customers found matching your criteria.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    let typingTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function() {
            document.getElementById('searchForm').submit();
        }, 500);
    });
</script>
@endsection
