@extends('layouts.app')

@section('title', 'Sign Up - Trust Rwanda')

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
        max-width: 500px;
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

    .tr-form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .tr-form-col {
        flex: 1;
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
        margin-top: 10px;
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

    .pass-criteria {
        list-style: none;
        padding: 0;
        margin: 8px 0 0 5px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .pass-criteria li {
        font-size: 12px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.3s;
    }
    .pass-criteria li.valid { color: #16a34a; }
    
    .tr-business-links {
        text-align: center; 
        margin-top: 20px; 
        border-top: 1px solid #e2e8f0; 
        padding-top: 20px;
    }
    
    @media (max-width: 576px) {
        .tr-login-wrapper {
            padding: 20px 15px;
        }
        .tr-login-container {
            padding: 30px 20px;
        }
        .tr-form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="tr-login-wrapper">
    <div class="tr-login-container">
        <div class="tr-logo-area">
            <i class="bi bi-cart4"></i>
        </div>
        
        <h1 class="tr-title">Create Account</h1>
        <p class="tr-subtitle">Create a new Trust Rwanda account.</p>

        @if ($errors->any())
            <div class="tr-alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i> 
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="tr-form-row">
                <div class="tr-form-col">
                    <div class="tr-form-group">
                        <label class="tr-form-label" for="nameInput">Full Name</label>
                        <div class="tr-input-wrapper">
                            <input type="text" name="full_name" id="nameInput" class="tr-form-control" placeholder="Enter Full Name" value="{{ old('full_name') }}" required>
                            <i class="bi bi-person tr-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="tr-form-col">
                    <div class="tr-form-group">
                        <label class="tr-form-label" for="phoneInput">Phone Number</label>
                        <div class="tr-input-wrapper">
                            <input type="tel" name="phone" id="phoneInput" class="tr-form-control" placeholder="Enter Phone" value="{{ old('phone') }}" required>
                            <i class="bi bi-telephone tr-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tr-form-group">
                <label class="tr-form-label" for="emailInput">Email ID</label>
                <div class="tr-input-wrapper">
                    <input type="email" name="email" id="emailInput" class="tr-form-control" placeholder="Enter Email ID" value="{{ old('email') }}" required>
                    <i class="bi bi-envelope tr-icon"></i>
                </div>
            </div>

            <div class="tr-form-row">
                <div class="tr-form-col">
                    <div class="tr-form-group" style="margin-bottom: 5px;">
                        <label class="tr-form-label" for="passInput">Password</label>
                        <div class="tr-input-wrapper">
                            <input type="password" name="password" id="passInput" class="tr-form-control" placeholder="Enter Password" required>
                            <i class="bi bi-lock tr-icon"></i>
                        </div>
                    </div>
                    <ul class="pass-criteria">
                        <li id="crit-upper"><i class="bi bi-circle"></i> Uppercase Letter</li>
                        <li id="crit-num"><i class="bi bi-circle"></i> Number</li>
                    </ul>
                </div>
                <div class="tr-form-col">
                    <div class="tr-form-group">
                        <label class="tr-form-label" for="confInput">Confirm Password</label>
                        <div class="tr-input-wrapper">
                            <input type="password" name="password_confirmation" id="confInput" class="tr-form-control" placeholder="Confirm Password" required>
                            <i class="bi bi-shield-check tr-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="tr-btn-submit">
                Sign Up <i class="bi bi-person-plus ms-1"></i>
            </button>
            
            <div class="tr-register-link">
                Already have an account? <a href="{{ route('login') }}">Login</a>
            </div>
            
            <div class="tr-business-links">
                <p style="color: #64748b; font-size: 13px; margin-bottom: 12px; font-weight: 600;">Are you a business?</p>
                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <a href="{{ route('vendor.register') }}" style="color: #4f46e5; font-size: 13px; font-weight: 700; text-decoration: none;"><i class="bi bi-shop me-1"></i> Vendor Register</a>
                    <span style="color: #cbd5e1;">|</span>
                    <a href="{{ route('property_owner.register') }}" style="color: #4f46e5; font-size: 13px; font-weight: 700; text-decoration: none;"><i class="bi bi-building me-1"></i> Real Estate Agent Register</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('passInput').addEventListener('input', function() {
    const val = this.value;
    const hasUpper = /[A-Z]/.test(val);
    const hasNum = /[0-9]/.test(val);

    updateCriterion('crit-upper', hasUpper);
    updateCriterion('crit-num', hasNum);
});

function updateCriterion(id, isValid) {
    const el = document.getElementById(id);
    const icon = el.querySelector('i');
    if (isValid) {
        el.classList.add('valid');
        icon.className = 'bi bi-check-circle-fill';
    } else {
        el.classList.remove('valid');
        icon.className = 'bi bi-circle';
    }
}
</script>
@endsection
