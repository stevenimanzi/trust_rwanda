@extends('layouts.app')

@section('title', 'Login - Trust Rwanda')

@section('styles')
<style>
    body {
        background-color: #dfdbed;
        margin: 0;
        padding-top: 100px;
    }
    
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 120px);
        padding: 20px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .login-card {
        display: flex;
        width: 1000px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .login-left {
        flex: 1;
        background: linear-gradient(180deg, #f2f7ff 0%, #e1fbef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        position: relative;
    }

    .login-left img.illustration {
        max-width: 100%;
        height: auto;
    }

    .login-right {
        flex: 1;
        padding: 60px 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-heading {
        color: #1a428a;
        font-weight: 800;
        font-size: 24px;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    .login-subheading {
        color: #b0b0b0;
        font-size: 14px;
        margin-bottom: 40px;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        color: #9e9e9e;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-field {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        color: #333;
        outline: none;
        transition: border-color 0.3s;
        box-sizing: border-box;
    }

    .input-field::placeholder {
        color: #d0d0d0;
    }

    .input-field:focus {
        border-color: #1a428a;
    }

    .input-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #b0b0b0;
        font-size: 18px;
    }

    .forgot-link {
        display: block;
        text-align: right;
        color: #1a428a;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        margin-bottom: 30px;
    }

    .login-btn {
        width: 100%;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        padding: 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .login-btn:hover {
        background-color: #1d4ed8;
    }

    .tr-alert {
        padding: 15px 20px;
        border-radius: 6px;
        margin-bottom: 25px;
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tr-alert-danger {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
            width: 100%;
        }
        .login-left {
            display: none;
        }
        .login-right {
            padding: 40px;
        }
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-left">
            <!-- Using an inline SVG illustration to match the design reference vibe -->
            <svg viewBox="0 0 400 300" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
                <!-- Monitor Base -->
                <rect x="80" y="80" width="240" height="150" rx="15" fill="#1a428a" />
                <rect x="90" y="90" width="220" height="130" fill="#ffffff" />
                <rect x="160" y="230" width="80" height="10" fill="#1a428a" />
                <!-- Login window inside monitor -->
                <rect x="120" y="110" width="160" height="90" rx="5" fill="#f8f9fa" stroke="#e0e0e0" stroke-width="2"/>
                <circle cx="200" cy="135" r="15" fill="#c4d2e8" />
                <circle cx="200" cy="135" r="8" fill="#1a428a" />
                <path d="M185 155 Q200 145 215 155" stroke="#1a428a" stroke-width="4" stroke-linecap="round" fill="none"/>
                
                <!-- Password dots -->
                <rect x="140" y="165" width="120" height="8" rx="4" fill="#1a428a" opacity="0.5"/>
                <rect x="140" y="180" width="120" height="8" rx="4" fill="#1a428a" opacity="0.5"/>
                
                <!-- Checkmark box -->
                <rect x="250" y="170" width="20" height="20" rx="3" fill="#ffb74d" />
                <path d="M255 180 L258 184 L265 175" stroke="#ffffff" stroke-width="2" fill="none" />
                
                <!-- Shield overlay -->
                <path d="M50 160 L100 160 L100 210 Q75 240 50 210 Z" fill="#ffb74d" />
                <path d="M55 165 L95 165 L95 205 Q75 230 55 205 Z" fill="#1a428a" />
                <!-- Lock in shield -->
                <rect x="68" y="185" width="14" height="12" rx="2" fill="#ffffff" />
                <path d="M71 185 V180 A 4 4 0 0 1 79 180 V185" stroke="#ffffff" stroke-width="2" fill="none" />
                
                <!-- Plant (Now Blue) -->
                <path d="M320 220 Q310 180 320 160 Q330 180 320 220" fill="#3b82f6" />
                <path d="M320 220 Q335 190 340 170 Q325 190 320 220" fill="#2563eb" />
                <path d="M320 220 Q305 190 300 170 Q315 190 320 220" fill="#60a5fa" />
                <rect x="305" y="220" width="30" height="20" rx="3" fill="#8c7ae6" />
                
                <!-- Paper planes -->
                <path d="M350 110 L370 120 L350 130 L355 120 Z" fill="#ffffff" />
                <path d="M80 110 L60 100 L80 90 L75 100 Z" fill="#ffffff" />
                
                <!-- Gear -->
                <circle cx="80" cy="130" r="12" fill="#6384c7" />
                <circle cx="80" cy="130" r="5" fill="#e1fbef" />
            </svg>
        </div>
        
        <div class="login-right">
            <h1 class="login-heading">LOGIN</h1>
            <p class="login-subheading">Welcome,<br>Please Login to your account</p>

            @if ($errors->any())
                <div class="tr-alert tr-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="identifierInput">Email ID</label>
                    <div class="input-wrapper">
                        <input type="text" name="identifier" id="identifierInput" class="input-field" placeholder="Enter Email ID" value="{{ old('identifier') }}" required autocomplete="username">
                        <i class="bi bi-person input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="passInput">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="passInput" class="input-field" placeholder="Enter Password" required autocomplete="current-password">
                        <i class="bi bi-lock input-icon"></i>
                    </div>
                </div>

                <a href="{{ route('password.request') }}" class="forgot-link">Forget Password?</a>

                <button type="submit" class="login-btn">LOGIN</button>
                
                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ route('register') }}" style="color: #1a428a; font-size: 12px; font-weight: 700; text-decoration: none;">Create an account</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
