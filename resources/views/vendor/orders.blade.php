@extends('layouts.vendor')

@section('title', 'Customer Orders')

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
    .st-pending { background: #fff1f2; color: #f43f5e; }
    .st-confirmed { background: #eff6ff; color: #3b82f6; }
    .st-shipped { background: #f3e8ff; color: #8b5cf6; }
    .st-delivered { background: #ecfdf5; color: #10b981; }
    .st-cancelled { background: #fef2f2; color: #ef4444; }
    
    .form-control-pro {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--hz-text-main);
        outline: none;
        transition: all 0.2s;
        background: var(--hz-bg);
    }
    .form-control-pro:focus {
        border-color: var(--hz-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        background: #fff;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="chart-title fs-4 mb-0">Customer Orders</div>
</div>

@if (session('msg'))
    <div class="alert alert-success shadow-sm rounded-3 mb-4 border-0" style="background: #ecfdf5; color: #10b981;"><i class="bi bi-check-circle me-2"></i>{{ session('msg') }}</div>
@elseif (session('error'))
    <div class="alert alert-danger shadow-sm rounded-3 mb-4 border-0" style="background: #fff1f2; color: #f43f5e;"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="ecom-card mb-4">
    <form action="{{ route('vendor.orders') }}" method="GET" class="d-flex gap-3 mb-4" id="searchForm">
        <div class="position-relative flex-grow-1" style="max-width: 400px;">
            <i class="bi bi-search position-absolute text-muted" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
            <input type="text" name="q" class="form-control-pro w-100 ps-5" placeholder="Search Order ID or Customer" value="{{ request('q') }}" id="searchInput">
        </div>
        
        <select name="status" class="form-control-pro" onchange="document.getElementById('searchForm').submit();">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
        </select>
    </form>

    <div class="table-responsive">
        <table class="ecom-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Total Value</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody id="orderTableBody">
                @if(isset($orders) && count($orders) > 0)
                    @foreach($orders as $order)
                    <tr>
                        <td class="fw-bold text-dark">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td><i class="bi bi-calendar3 text-muted me-1"></i> {{ date('j M y, h:i A', strtotime($order->created_at)) }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $order->full_name ?? 'Guest' }}</div>
                        </td>
                        <td>RWF {{ number_format($order->my_revenue) }}</td>
                        <td>
                            <span class="status-pill st-{{ strtolower($order->delivery_status) }}">
                                {{ $order->delivery_status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('vendor.orders.details', $order->id) }}" class="btn btn-sm btn-light border-0" style="background: #f8fafc; color: #8b5cf6;"><i class="bi bi-eye-fill"></i> View Details</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="6" class="text-center py-5 text-muted">No orders found matching your criteria.</td></tr>
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
