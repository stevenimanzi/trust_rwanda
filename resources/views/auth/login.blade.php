@extends('layouts.app')

@section('title', 'Login | Trust Rwanda')

@section('styles')
<style>
    .tr-login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 200px);
        padding: 40px 20px;
        background-color: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .tr-login-container {
        width: 100%;
        max-width: 400px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        padding: 40px;
        box-sizing: border-box;
    }

    .tr-logo-area {
        text-align: center;
        margin-bottom: 25px;
    }

    .tr-logo-area i {
        font-size: 36px;
        color: #4f46e5;
    }

    .tr-title {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        text-align: center;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .tr-subtitle {
        color: #64748b;
        font-size: 14px;
        text-align: center;
        margin-bottom: 30px;
    }

    .tr-form-group {
        margin-bottom: 20px;
    }

    .tr-form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .tr-input-wrapper {
        position: relative;
    }

    .tr-form-control {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        color: #0f172a;
        transition: all 0.2s ease;
        background: #fff;
        box-sizing: border-box;
    }

    .tr-form-control:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .tr-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        transition: color 0.2s;
    }

    .tr-form-control:focus + .tr-icon {
        color: #4f46e5;
    }

    .tr-forgot {
        text-align: right;
        margin-top: 8px;
        margin-bottom: 25px;
    }

    .tr-forgot a {
        color: #4f46e5;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .tr-forgot a:hover {
        text-decoration: underline;
    }

    .tr-btn-submit {
        width: 100%;
        padding: 14px;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .tr-btn-submit:hover {
        background: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .tr-register-link {
        text-align: center;
        margin-top: 25px;
        color: #64748b;
        font-size: 14px;
    }

    .tr-register-link a {
        color: #4f46e5;
        font-weight: 700;
        text-decoration: none;
    }

    .tr-register-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 576px) {
        .tr-login-wrapper {
            padding: 20px 15px;
        }
        .tr-login-container {
            padding: 30px 20px;
        }
        .tr-title {
            font-size: 22px;
        }
    }

    .tr-alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 25px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
</style>
@endsection

@section('content')
<div class="tr-login-wrapper">
    <div class="tr-login-container">
        <div class="tr-logo-area">
            <i class="bi bi-cart4"></i>
        </div>
    
    <h1 class="tr-title">Welcome Back!</h1>
    <p class="tr-subtitle">Sign in to your Trust Rwanda account.</p>

    @if ($errors->any())
        <div class="tr-alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i> 
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="tr-form-group">
            <label class="tr-form-label" for="identifierInput">Email Address</label>
            <div class="tr-input-wrapper">
                <input type="text" name="identifier" id="identifierInput" class="tr-form-control" placeholder="Enter your email" value="{{ old('identifier') }}" required autocomplete="username">
                <i class="bi bi-envelope tr-icon"></i>
            </div>
        </div>

        <div class="tr-form-group" style="margin-bottom: 0;">
            <label class="tr-form-label" for="passInput">Password</label>
            <div class="tr-input-wrapper">
                <input type="password" name="password" id="passInput" class="tr-form-control" placeholder="••••••••" required autocomplete="current-password">
                <i class="bi bi-lock tr-icon"></i>
            </div>
        </div>

        <div class="tr-forgot">
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="tr-btn-submit">
            Sign In <i class="bi bi-box-arrow-in-right"></i>
        </button>
        
        <div class="tr-register-link">
            Don't have an account? <a href="{{ route('register') }}">Sign up here</a>
        </div>
    </form>
    </div>
</div>
@endsection
