@extends('layouts.app')

@section('title', 'Set New Password - Trust Rwanda')

@section('styles')
<style>
    .reset-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .reset-card {
        border: none;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        max-width: 1000px;
        width: 100%;
        background: white;
    }

    /* Left Branding Side */
    .brand-side {
        background-color: var(--primary-dark);
        color: white;
        padding: 4.5rem 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
        position: relative;
    }

    .brand-side::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');
        opacity: 0.05;
    }

    .brand-icon-circle {
        width: 80px; height: 80px;
        background: rgba(255, 255, 255, 0.1);
        display: flex; align-items: center; justify-content: center;
        border-radius: 22px; margin: 0 auto 2rem auto;
        backdrop-filter: blur(5px);
    }

    /* Right Form Side */
    .form-side { background: white; padding: 4.5rem 4rem; }

    .btn-reset-finish {
        background: linear-gradient(135deg, var(--primary-color) 0%, #3730a3 100%);
        color: white; border: none; padding: 1rem;
        font-weight: 700; border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-reset-finish:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -5px rgba(79, 70, 229, 0.4);
        color: white;
    }

    .input-group-premium {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .input-group-premium i {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 10;
        transition: color 0.2s;
    }

    .input-group-premium .form-control {
        padding-left: 3.5rem;
        height: 60px;
        border-radius: 16px;
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        font-weight: 600;
        transition: all 0.2s;
    }

    .input-group-premium .form-control:focus {
        background-color: white;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
    }

    .input-group-premium .form-control:focus + i {
        color: var(--primary-color);
    }

    .status-badge {
        font-size: 0.75rem;
        background: rgba(129, 140, 248, 0.1);
        color: var(--vendor-accent);
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        display: inline-block;
    }

    @media (max-width: 991px) {
        .brand-side { display: none; }
        .form-side { padding: 3rem 2rem; }
    }
</style>
@endsection

@section('content')
<div class="container reset-wrapper">
    <div class="reset-card card">
        <div class="row g-0">
            
            <div class="col-lg-5 brand-side">
                <div class="brand-icon-circle" style="padding: 10px; background: white; width: 100px; height: 100px;">
                    <img src="{{ asset('assets/uploads/logos/TrustRwanda-Logo.jpg') }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 50%;" onerror="this.src='{{ asset('assets/uploads/logos/TrustRwanda-Logo.png') }}'">
                </div>
                <h2 class="fw-bold mb-3">Security Reset</h2>
                <p class="text-white-50 mb-4 px-lg-3">Your password change will take effect immediately upon verification.</p>
                
                <div class="mt-4">
                     <span class="status-badge">
                        <i class="bi bi-patch-check-fill me-1"></i> Token Verified
                     </span>
                </div>
            </div>
            
            <div class="col-lg-7 form-side">
                <div class="mb-5">
                    <h3 class="fw-bold text-dark mb-1">Set New Password</h3>
                    <p class="text-muted small">Choose a strong password containing at least 6 characters.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 d-flex align-items-center small py-3 mb-4 border-0" style="background: #fff1f2; color: #be123c;">
                        <i class="bi bi-exclamation-circle-fill me-3 fs-5"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token ?? '' }}">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted ms-2 text-uppercase" style="letter-spacing: 0.5px;">New Password</label>
                        <div class="input-group-premium">
                            <input type="password" name="password" class="form-control shadow-none" placeholder="Min. 6 characters" required>
                            <i class="bi bi-lock-fill"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted ms-2 text-uppercase" style="letter-spacing: 0.5px;">Confirm Password</label>
                        <div class="input-group-premium">
                            <input type="password" name="password_confirmation" class="form-control shadow-none" placeholder="Repeat new password" required>
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-reset-finish btn-lg">
                            Update My Password <i class="bi bi-check2-circle ms-2"></i>
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-muted text-decoration-none small fw-bold">
                            <i class="bi bi-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
