@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-4 text-primary">Terms of Service</h1>
            <p class="text-muted">Last updated: {{date('F d, Y')}}</p>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h4 class="fw-bold">1. Acceptance of Terms</h4>
                    <p class="text-muted small">By accessing and using Trust Rwanda, you accept and agree to be bound by the terms and provision of this agreement. In addition, when using this platform's particular services, you shall be subject to any posted guidelines or rules applicable to such services.</p>
                    
                    <h4 class="fw-bold mt-4">2. Vendor Responsibilities</h4>
                    <p class="text-muted small">Vendors are solely responsible for the accuracy of their product listings, including prices, availability, and descriptions. Any fraudulent activity will result in immediate account termination.</p>

                    <h4 class="fw-bold mt-4">3. Limitation of Liability</h4>
                    <p class="text-muted small">Trust Rwanda shall not be liable for any indirect, incidental, special, consequential or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
