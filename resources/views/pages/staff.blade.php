@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">Our Staff</h1>
                <p class="lead text-muted">The dedicated professionals keeping Trust Rwanda running smoothly every day.</p>
            </div>
            
            <div class="row g-4">
                @for($i = 1; $i <= 6; $i++)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body p-4">
                            <div class="mb-3 d-inline-block p-4 bg-light rounded-circle text-primary">
                                <i class="bi bi-person-fill display-5"></i>
                            </div>
                            <h4 class="fw-bold mb-1">Staff Member {{$i}}</h4>
                            <p class="text-muted small mb-3">Department Specialist</p>
                            <p class="small">Dedicated to ensuring operational excellence and providing top-tier service to our vendors and buyers across Rwanda.</p>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection
