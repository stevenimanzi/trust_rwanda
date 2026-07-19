@extends('layouts.app')

@section('title', 'Forgot Password - Trust Rwanda')

@section('styles')
<style>
    .main-box {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .custom-card {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        max-width: 950px;
        width: 100%;
        background: white;
    }

    .info-pane {
        background-color: var(--primary-dark);
        color: white;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .icon-circle {
        width: 70px; height: 70px;
        background: rgba(255, 255, 255, 0.1);
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; margin: 0 auto 25px auto;
        font-size: 30px; color: var(--vendor-accent);
    }

    .form-pane { padding: 60px; }

    .input-field-group { position: relative; margin-bottom: 25px; }

    .input-field-group i {
        position: absolute; left: 20px; top: 50%;
        transform: translateY(-50%); color: #94a3b8;
        font-size: 18px; transition: 0.3s;
    }

    .input-field-group .form-control {
        padding-left: 55px; height: 60px;
        border-radius: 15px; border: 2px solid #f1f5f9;
        background: #f8fafc; font-weight: 500; transition: 0.3s;
    }

    .input-field-group .form-control:focus {
        background-color: white; border-color: var(--primary-color);
        box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.05);
    }

    .btn-main {
        background-color: var(--primary-color); color: white;
        border: none; padding: 15px; font-weight: 700;
        border-radius: 15px; transition: 0.3s; width: 100%;
    }

    .btn-main:hover {
        background-color: #4338ca; transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
    }

    @media (max-width: 768px) {
        .info-pane { display: none; }
        .form-pane { padding: 35px 25px; }
    }
</style>
@endsection

@section('content')
<div class="container main-box">
    <div class="custom-card">
        <div class="row g-0">
            
            <div class="col-md-5 info-pane">
                <div class="icon-circle" style="padding: 10px; background: white; width: 90px; height: 90px;">
                    <img src="{{ asset('assets/uploads/logos/TrustRwanda-Logo.jpg') }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 50%;" onerror="this.src='{{ asset('assets/uploads/logos/TrustRwanda-Logo.png') }}'">
                </div>
                <h3 class="fw-bold mb-3">Lost Password</h3>
                <p class="text-white-50">Enter details to recover your account safety credentials.</p>
                
                <div class="mt-5 pt-4 border-top border-white border-opacity-10">
                    <p class="small text-white-50 mb-2">Remember your password?</p>
                    <a href="{{ route('login') }}" class="text-white fw-bold text-decoration-none">
                         <i class="bi bi-arrow-left"></i> Back to login
                    </a>
                </div>
            </div>
            
            <div class="col-md-7 form-pane">
                <div class="mb-5">
                    <h2 class="fw-bold text-dark mb-1">Account Recovery</h2>
                    <p class="text-muted">Enter your email or phone number to retrieve your custom security reset link.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm p-4 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-2 me-3 text-success"></i>
                            <span class="fw-medium">{!! session('status') !!}</span>
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill fw-bold">Done, Go to Login</a>
                    </div>
                @else

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 border-0 d-flex align-items-center p-3 mb-4">
                            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i> 
                            <strong>{{ $errors->first() }}</strong>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted mb-2">Email or Phone Number</label>
                            <div class="input-field-group">
                                <input type="text" name="identifier" class="form-control shadow-none" placeholder="e.g. name@mail.com" required>
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-main">
                                Send Reset Link <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-muted text-decoration-none small fw-bold">
                                Nevermind, go back
                            </a>
                        </div>
                    </form>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
