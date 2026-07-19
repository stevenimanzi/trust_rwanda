@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-4 text-primary">Help & Support</h1>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-3">How can we help you today?</h3>
                    <p class="text-muted mb-4">Welcome to the Trust Rwanda support center. We're here to ensure your experience on our marketplace is seamless and successful.</p>
                    
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    How do I track my order?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can track your order by logging into your account, navigating to your dashboard, and clicking on the "Orders" tab. Alternatively, check your email for the tracking link provided upon dispatch.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    How do I list a property?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Click on "List Your Property" in the footer or navigation menu. Register as a Property Owner, and you'll gain access to a dedicated dashboard where you can manage your listings, images, and prices.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    What payment methods are supported?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We support a variety of payment methods including MTN Mobile Money, Airtel Money, BK Bank transfers, and standard credit/debit cards via secure payment gateways.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-headset display-4 mb-3"></i>
                    <h3 class="fw-bold">Still need help?</h3>
                    <p>Our dedicated support team is available 24/7.</p>
                    <a href="mailto:{{$supportEmail ?? 'support@trustrwanda.com'}}" class="btn btn-light btn-lg mt-2 fw-bold text-primary">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
