@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">Leadership Team</h1>
                <p class="lead text-muted">Meet the visionaries building Rwanda's premier digital marketplace.</p>
            </div>
            
            <!-- Team Member 1 -->
            <div class="row g-5 align-items-center mb-5 border-bottom pb-5">
                <div class="col-md-6">
                    <div class="bg-light rounded p-5 text-center">
                        <i class="bi bi-person-bounding-box display-1 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bold">Steven IMANZI</h2>
                    <h5 class="text-primary mb-4">Founder & Lead Developer</h5>
                    <p class="text-muted">Steven leads the technical vision and product strategy at Trust Rwanda. With a passion for scalable systems and world-class UI/UX, he ensures the platform remains at the cutting edge of e-commerce and real estate technology in East Africa.</p>
                    <a href="https://stevenimanzi.kesug.com" target="_blank" class="btn btn-outline-primary">Visit Portfolio &rarr;</a>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="row g-5 align-items-center mb-5 flex-md-row-reverse">
                <div class="col-md-6">
                    <div class="bg-light rounded p-5 text-center">
                        <i class="bi bi-cpu display-1 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <h2 class="fw-bold">Quata Yves</h2>
                    <h5 class="text-primary mb-4">Partner, AI & Electronics Engineer</h5>
                    <p class="text-muted">Yves drives Trust Rwanda's technological innovation with his deep expertise in Artificial Intelligence and electronics engineering. Additionally, his vast experience in social media advertising spearheads the platform's digital growth and market reach.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
