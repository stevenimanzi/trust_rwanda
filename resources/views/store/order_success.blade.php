@extends('layouts.app')

@section('title', 'Order Placed Successfully | Trust Rwanda')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="success-card text-center">
                
                <div class="order-status-timeline">
                    <div class="status-dot active"></div>
                    <div class="status-dot active"></div>
                    <div class="status-dot animate-pulse" style="background: #4F46E5;"></div>
                </div>

                <div class="mb-4">
                    <i class="bi bi-check-all confetti-glow"></i>
                </div>
                
                <h1 class="fw-800 mb-2">Order Confirmed!</h1>
                <div class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-4">
                    Order ID: #{{ $latestOrder['id'] }}
                </div>

                <p class="text-muted px-md-5 mb-4">
                    Your Pesapal payment has been received and your order is confirmed. The vendor will use your delivery details to arrange fulfillment.
                </p>

                <div class="address-badge mb-5">
                    <i class="bi bi-truck text-primary"></i>
                    <span>Delivery Address: <b>{{ $latestOrder['address'] }}</b></span>
                </div>

                <div class="d-flex justify-content-center gap-4 mb-2">
                    <span class="fw-bold text-dark">Total paid</span>
                    <span class="fw-bold text-success">{{ number_format($latestOrder['total']) }} RWF</span>
                </div>

                <div class="mt-5 border-top pt-5">
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-5">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 rounded-pill py-3 fw-800">
                                <i class="bi bi-house me-2"></i> Return Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="bi bi-shield-lock me-1"></i> Your delivery details are safely processed.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .success-card {
        background: white;
        border-radius: 35px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        padding: 4rem 2rem;
    }
    .order-status-timeline {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
    }
    .status-dot {
        width: 10px; height: 10px; border-radius: 50%;
        background: #e2e8f0;
    }
    .status-dot.active { background: #10B981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5); }
    
    .vendor-card {
        background: #ffffff;
        border: 2px solid #f1f5f9;
        border-radius: 24px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .vendor-card:hover {
        border-color: #4F46E5;
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(79, 70, 229, 0.08);
    }
    .address-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 12px 20px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
    }
    .wa-btn {
        background: #22c55e;
        color: white;
        border: none;
        transition: 0.3s;
    }
    .wa-btn:hover { background: #16a34a; transform: scale(1.05); color: white; }
    
    .confetti-glow {
        font-size: 5rem;
        color: #10B981;
        filter: drop-shadow(0 0 20px rgba(16, 185, 129, 0.3));
    }
</style>
@endsection
