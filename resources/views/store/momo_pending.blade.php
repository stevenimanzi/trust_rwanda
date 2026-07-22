@extends('layouts.app')

@section('title', 'Confirm MTN MoMo Payment | Trust Rwanda')

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5 text-center">
            <div class="mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 76px; height: 76px; border-radius: 50%; background: #ffcc00; color: #111;">
                <i class="bi bi-phone-vibrate fs-1"></i>
            </div>
            <h1 class="h3 fw-bold">Approve payment on your phone</h1>
            <p class="text-muted mb-4">An MTN MoMo prompt was sent to <strong>{{ $order->delivery_phone }}</strong>. Enter your PIN on your phone to approve {{ number_format($total) }} RWF.</p>

            <div id="paymentState" class="alert alert-warning border-0 py-3" role="status">
                <span class="spinner-border spinner-border-sm me-2"></span> Waiting for MTN confirmation...
            </div>

            <p class="small text-muted">Order reference: {{ $order->transaction_id }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4">Continue shopping</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const state = document.getElementById('paymentState');
let attempts = 0;
const checkPayment = async () => {
    attempts++;
    try {
        const response = await fetch(@json(route('mtn-momo.status', ['reference' => $reference])), {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();
        if (result.status === 'SUCCESSFUL') {
            state.className = 'alert alert-success border-0 py-3';
            state.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Payment confirmed. Your order is paid.';
            return;
        }
        if (['FAILED', 'REJECTED', 'EXPIRED'].includes(result.status)) {
            state.className = 'alert alert-danger border-0 py-3';
            state.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i> Payment was not completed. Please return to checkout and try again.';
            return;
        }
    } catch (error) {
        // A later poll can recover from a temporary connection problem.
    }
    if (attempts < 60) setTimeout(checkPayment, 5000);
    else {
        state.className = 'alert alert-secondary border-0 py-3';
        state.textContent = 'Confirmation is taking longer than expected. Your order remains pending.';
    }
};
setTimeout(checkPayment, 2500);
</script>
@endsection
