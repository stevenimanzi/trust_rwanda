@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="display-5 fw-bold mb-5 text-primary text-center">Trust Rwanda Blog</h1>
            
            <div class="row g-4">
                @for($i = 1; $i <= 6; $i++)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-img-top bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-image text-muted display-4"></i>
                        </div>
                        <div class="card-body p-4">
                            <span class="badge bg-primary mb-2">Market Insights</span>
                            <h5 class="fw-bold">How to scale your online business in Rwanda</h5>
                            <p class="text-muted small mb-3">Published on {{date('M d, Y')}}</p>
                            <p class="card-text">Discover the top strategies for reaching a wider audience using the Trust Rwanda vendor tools...</p>
                            <a href="#" class="text-primary text-decoration-none fw-bold">Read Article &rarr;</a>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection
