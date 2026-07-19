@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-4 text-primary">Privacy Policy</h1>
            <p class="text-muted">Last updated: {{date('F d, Y')}}</p>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h4 class="fw-bold">1. Information We Collect</h4>
                    <p class="text-muted small">We collect information you provide directly to us, such as when you create or modify your account, request on-demand services, contact customer support, or otherwise communicate with us. This information may include: name, email, phone number, postal address, profile picture, payment method, items requested (for delivery services), and other information you choose to provide.</p>
                    
                    <h4 class="fw-bold mt-4">2. How We Use Information</h4>
                    <p class="text-muted small">We may use the information we collect about you to: Provide, maintain, and improve our Services; Perform internal operations; Send you communications; Personalize and improve the Services.</p>

                    <h4 class="fw-bold mt-4">3. Sharing of Information</h4>
                    <p class="text-muted small">We may share the information we collect about you with vendors, consultants, marketing partners, and other service providers who need access to such information to carry out work on our behalf.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
