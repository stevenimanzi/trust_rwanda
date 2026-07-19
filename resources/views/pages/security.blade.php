@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-4 text-primary"><i class="bi bi-shield-check me-2"></i>Security</h1>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">Your safety is our priority</h3>
                    <p class="text-muted">At Trust Rwanda, we employ industry-leading security measures to protect your data, your transactions, and your peace of mind.</p>
                    
                    <hr class="my-4">
                    
                    <h5 class="fw-bold"><i class="bi bi-lock text-success me-2"></i> Data Encryption</h5>
                    <p class="text-muted small">All data transmitted between your browser and our servers is encrypted using 256-bit SSL/TLS protocols, ensuring that your personal and financial information remains private.</p>
                    
                    <h5 class="fw-bold mt-4"><i class="bi bi-credit-card text-primary me-2"></i> Secure Payments</h5>
                    <p class="text-muted small">We partner with verified payment gateways (MTN MoMo, Airtel Money, BK) that are PCI-DSS compliant. We do not store your raw credit card data on our servers.</p>
                    
                    <h5 class="fw-bold mt-4"><i class="bi bi-person-check text-warning me-2"></i> Vendor Verification</h5>
                    <p class="text-muted small">Every property owner and vendor on Trust Rwanda undergoes a rigorous manual verification process before they are allowed to list products, drastically reducing fraud.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
