@extends('layouts.app')

@section('title', 'Payment Unavailable | Trust Rwanda')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5 text-center">
            <div class="d-flex align-items-center justify-content-center mx-auto mb-4 bg-danger-subtle text-danger" style="width: 72px; height: 72px; border-radius: 50%;">
                <i class="bi bi-exclamation-triangle fs-2"></i>
            </div>
            <h1 class="h3 fw-bold mb-3">Payment could not start</h1>
            <p class="text-muted mb-2">{{ $message }}</p>
            <p class="small text-muted mb-4">Order reference: {{ $orderId }}</p>
            <div class="d-grid gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-primary py-3 fw-bold">Return to cart</a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary py-3">Continue shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
