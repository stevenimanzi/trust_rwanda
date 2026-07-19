@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="display-5 fw-bold mb-4 text-primary"><i class="bi bi-bar-chart-line me-2"></i>Market Charts</h1>
            <p class="text-muted mb-5">Analyze market trends in Rwanda's growing real estate and e-commerce sectors.</p>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold mb-4">Real Estate Price Trends (2025-2026)</h5>
                            <!-- Placeholder Chart UI -->
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                                <i class="bi bi-graph-up text-primary display-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold mb-4">E-commerce Sales Volume</h5>
                            <!-- Placeholder Chart UI -->
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                                <i class="bi bi-pie-chart text-success display-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
