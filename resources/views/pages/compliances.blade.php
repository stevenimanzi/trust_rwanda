@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-4 text-primary"><i class="bi bi-file-earmark-check me-2"></i>Compliances</h1>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">Regulatory Compliance</h3>
                    <p class="text-muted mb-5">Trust Rwanda operates strictly within the legal frameworks of the Republic of Rwanda and international digital commerce standards.</p>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Data Protection Law</h6>
                                <p class="text-muted small mb-0">Compliant with Rwanda's Law No. 058/2021 relating to the protection of personal data and privacy.</p>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">RRA Tax Compliance</h6>
                                <p class="text-muted small mb-0">Integrated with Rwanda Revenue Authority (RRA) EBM systems for transparent tax reporting.</p>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">RDB Business Registration</h6>
                                <p class="text-muted small mb-0">Fully registered and licensed e-commerce and real estate brokerage entity by the Rwanda Development Board.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
