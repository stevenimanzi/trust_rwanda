@extends('layouts.vendor')

@section('title', 'Order Details #' . $order->id)

@section('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 24px;
        border: none;
        margin-bottom: 24px;
        height: 100%;
    }
    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--hz-text-main);
        margin-bottom: 20px;
    }
    
    .product-thumb { 
        width: 44px; height: 44px; border-radius: 8px; 
        object-fit: cover; background: #f8fafc; border: 1px solid #f1f5f9; 
    }

    .form-label-pro {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-select-pro {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--hz-text-main);
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .form-select-pro:focus {
        border-color: var(--hz-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        background: #ffffff;
    }
    
    .btn-ecom {
        background: var(--hz-primary);
        color: white;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 600;
        border: none;
        transition: opacity 0.2s;
    }
    .btn-ecom:hover {
        opacity: 0.9;
        color: white;
    }

    /* Printable Invoice Styles */
    @media print {
        .no-print { display: none !important; }
        #printableInvoice { display: block !important; padding: 20px; }
        body { background: white !important; padding: 0 !important; }
        .hz-sidebar { display: none !important; }
        .hz-topnav { display: none !important; }
    }
    #printableInvoice { display: none; }
</style>
@endsection

@section('content')
<!-- Printable Area -->
<div id="printableInvoice">
    <div class="d-flex justify-content-between mb-5 border-bottom pb-4">
        <div>
            <h2 class="fw-bold text-primary m-0" style="color: #8b5cf6 !important;">{{ strtoupper(auth()->user()->shop_name ?? 'My Shop') }}</h2>
            <small class="text-muted">Trust Rwanda Verified Store</small>
        </div>
        <div class="text-end">
            <h4 class="fw-bold m-0">INVOICE</h4>
            <p class="small m-0">#ORDER-{{ $order->id }}</p>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-6">
            <label class="small text-muted fw-bold text-uppercase">Customer</label>
            <h6 class="fw-bold">{{ $order->customer_name }}</h6>
            <p class="small m-0">{{ $order->customer_phone }}</p>
        </div>
        <div class="col-6 text-end">
            <label class="small text-muted fw-bold text-uppercase">Payment Status</label>
            <h6 class="text-success fw-bold">{{ strtoupper($order->payment_status ?? 'PAID') }}</h6>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="bg-light">
                <th>Item Description</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i)
                <tr>
                    <td>{{ $i->title }}</td>
                    <td class="text-center">{{ $i->quantity }}</td>
                    <td class="text-end fw-bold">{{ number_format($i->quantity * $i->price_at_purchase) }} RWF</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center mt-5 pt-5">
        <small class="text-muted">Thank you for your business. Generated via Trust Rwanda Intelligence.</small>
    </div>
</div>

<!-- Public Layout (Screen Only) -->
<div class="container-fluid py-4 py-lg-5 no-print">
    
    <!-- Top Nav Action Row -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('vendor.orders') }}" class="btn btn-outline-secondary btn-sm fw-bold d-flex align-items-center gap-2 px-3 py-2" style="border-radius:8px;">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
        <button class="btn-ecom d-flex align-items-center gap-2" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Invoice
        </button>
    </div>

    @if (session('msg'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4 border-0" style="background: #ecfdf5; color: #10b981;"><i class="bi bi-check-circle me-2"></i>{{ session('msg') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger shadow-sm rounded-3 mb-4 border-0" style="background: #fff1f2; color: #f43f5e;"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <!-- Left Panel: Line Items Grid -->
        <div class="col-lg-8">
            <div class="ecom-card">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <span class="chart-title m-0"><i class="bi bi-receipt text-primary me-2"></i>Line Items</span>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: #f3e8ff; color: #8b5cf6; font-size:0.75rem;">Order #{{ $order->id }}</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead>
                            <tr class="small text-muted text-uppercase" style="font-size: 0.75rem;">
                                <th class="border-0">Product</th>
                                <th class="border-0 text-center">Qty</th>
                                <th class="border-0 text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $i)
                                @php
                                    $img = $i->image_url;
                                    $imgSrc = $img ? asset('assets/uploads/products/' . $img) : 'https://placehold.co/100?text=No+Media';
                                    if ($img && str_starts_with($img, 'http')) {
                                        $imgSrc = $img;
                                    }
                                @endphp
                                <tr>
                                    <td class="border-light border-bottom">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $imgSrc }}" class="product-thumb" onerror="this.src='https://placehold.co/100?text=No+Media'">
                                            <span class="fw-bold text-dark">{{ $i->title }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold border-light border-bottom">x{{ $i->quantity }}</td>
                                    <td class="text-end fw-bold text-dark border-light border-bottom">RWF {{ number_format($i->quantity * $i->price_at_purchase) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Panel: Processing Matrix -->
        <div class="col-lg-4">
            <!-- Customer Summary -->
            <div class="ecom-card mb-4" style="height: auto;">
                <span class="chart-title">Delivery Route</span>
                
                <div class="mb-3">
                    <label class="form-label-pro">End Consumer</label>
                    <div class="fw-bold text-dark">{{ $order->customer_name ?? 'Guest User' }}</div>
                    <div class="text-muted small"><i class="bi bi-telephone-fill me-1"></i> {{ $order->customer_phone ?? 'N/A' }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label-pro">Delivery Checkpoint</label>
                    <div class="small fw-bold p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0; color:#475569;">
                        {{ $order->delivery_address ?? 'Pickup from vendor directly or coordinate via call.' }}
                    </div>
                </div>
            </div>

            <!-- Fulfillment Controller -->
            <div class="ecom-card" style="height: auto;">
                <span class="chart-title">Status Control</span>

                <form method="POST" action="{{ route('vendor.orders.update_status', $order->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label-pro">Fulfillment Phase</label>
                        <select name="status" class="form-select form-select-pro w-100" required>
                            <option value="pending" {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>Pending Prep</option>
                            <option value="confirmed" {{ $order->delivery_status == 'confirmed' ? 'selected' : '' }}>Confirmed (Ready)</option>
                            <option value="shipped" {{ $order->delivery_status == 'shipped' ? 'selected' : '' }}>Shipped (En Route)</option>
                            <option value="delivered" {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered (Completed)</option>
                            <option value="cancelled" {{ $order->delivery_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-ecom w-100">
                        Commit Status Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
