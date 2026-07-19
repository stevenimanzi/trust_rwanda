@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-4 text-primary">Careers at Trust Rwanda</h1>
            
            <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-3">Build the future of commerce</h2>
                    <p class="lead mb-0">We are always looking for passionate, driven individuals to join our team in Kigali and beyond.</p>
                </div>
            </div>

            <h3 class="fw-bold mb-4">Open Positions</h3>
            
            <div class="list-group list-group-flush shadow-sm rounded mb-5">
                <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Senior Full Stack Developer</h5>
                        <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> Kigali (Hybrid) &bull; Engineering</p>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
                <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Regional Marketing Manager</h5>
                        <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> Kigali &bull; Marketing</p>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
                <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Customer Success Specialist</h5>
                        <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> Remote &bull; Support</p>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            </div>
            
            <p class="text-center text-muted">Don't see a role that fits? Send your resume to <a href="mailto:careers@trustrwanda.com">careers@trustrwanda.com</a>.</p>
        </div>
    </div>
</div>
@endsection
