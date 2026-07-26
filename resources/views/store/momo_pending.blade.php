@extends('layouts.app')

@section('title', 'Processing Payment | Trust Rwanda')

@section('styles')
<style>
    .momo-container {
        max-width: 500px;
        margin: 80px auto;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        padding: 40px;
        text-align: center;
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }
    
    .momo-logo-box {
        width: 100px;
        height: 100px;
        background: #ffcc00;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px auto;
        box-shadow: 0 8px 25px rgba(255, 204, 0, 0.4);
        position: relative;
    }
    
    .momo-logo-box::after {
        content: '';
        position: absolute;
        top: -10px; left: -10px; right: -10px; bottom: -10px;
        border: 2px dashed #ffcc00;
        border-radius: 50%;
        animation: rotate 10s linear infinite;
        opacity: 0.5;
    }

    @keyframes rotate {
        100% { transform: rotate(360deg); }
    }

    .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        background: #ffcc00;
        border-radius: 50%;
        z-index: -1;
        animation: pulse 2s ease-out infinite;
        opacity: 0;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(1.8); opacity: 0; }
    }

    .momo-title {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .momo-desc {
        color: #64748b;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 30px;
    }
    
    .instruction-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        text-align: left;
        margin-bottom: 30px;
    }
    
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 15px;
    }
    .step-item:last-child { margin-bottom: 0; }
    
    .step-number {
        background: #0f172a;
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .step-text {
        color: #334155;
        font-weight: 600;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .amount-display {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="momo-container">
        
        <div class="momo-logo-box">
            <div class="pulse-ring"></div>
            <i class="bi bi-phone-vibrate fs-1 text-dark"></i>
        </div>

        <div class="amount-display">
            {{ number_format($totalAmount) }} RWF
        </div>

        <h2 class="momo-title">Awaiting Approval</h2>
        <p class="momo-desc">A USSD payment prompt has been pushed to your mobile phone (<strong>{{ $momoPhone }}</strong>). Please check your phone to complete the payment.</p>

        <div class="instruction-box">
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-text">Unlock your mobile phone.</div>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-text">Wait for the Mobile Money prompt to appear on your screen.</div>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-text">Enter your Mobile Money PIN to authorize the payment of <strong>{{ number_format($totalAmount) }} RWF</strong>.</div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 text-primary fw-bold">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Waiting for confirmation...
        </div>
        
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Polling logic to check payment status automatically
    let checkInterval = setInterval(function() {
        fetch('{{ route("api.momo.status", $transactionId) }}')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'PAID' || data.status === 'SUCCESS') {
                    clearInterval(checkInterval);
                    // Redirect to success page
                    window.location.href = '{{ route("order.success") }}';
                } else if (data.status === 'FAILED') {
                    clearInterval(checkInterval);
                    alert("Payment failed or was cancelled.");
                    window.location.href = '{{ route("checkout.index") }}';
                }
            })
            .catch(error => console.error('Error checking status:', error));
    }, 3000); // Check every 3 seconds
</script>
@endsection
