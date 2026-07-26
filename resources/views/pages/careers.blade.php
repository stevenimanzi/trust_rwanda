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
            
            <div class="card border-0 shadow-sm rounded mb-5 text-center p-5 bg-light">
                <i class="bi bi-briefcase text-muted mb-3" style="font-size: 3rem;"></i>
                <h4 class="fw-bold text-dark">No Open Positions</h4>
                <p class="text-muted mb-0">We currently do not have any open positions available. Please check back later as we are constantly growing!</p>
            </div>
            
            <p class="text-center text-muted">Want to proactively join the team? Send your resume to <a href="mailto:careers@trustrwanda.com">careers@trustrwanda.com</a>.</p>
        </div>
    </div>
</div>
@endsection
