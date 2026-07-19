@extends('layouts.app')

@section('title', 'Sign Up - Trust Rwanda')

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

    .login-right {
        flex: 1;
        padding: 50px 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-heading {
        color: #1a428a;
        font-weight: 800;
        font-size: 24px;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }

    .login-subheading {
        color: #b0b0b0;
        font-size: 14px;
        margin-bottom: 30px;
        line-height: 1.5;
    }

    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .form-col {
        flex: 1;
    }

    .form-group {
        margin-bottom: 20px;
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
        font-size: 13px;
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
        font-size: 16px;
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
        margin-top: 10px;
    }

    .login-btn:hover {
        background-color: #1d4ed8;
    }
    
    .pass-criteria {
        list-style: none;
        padding: 0;
        margin: 5px 0 0 5px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .pass-criteria li {
        font-size: 11px;
        color: #b0b0b0;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: color 0.3s;
    }
    .pass-criteria li.valid { color: #559b38; }

    .tr-alert {
        padding: 12px 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 12px;
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
            padding: 30px;
        }
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-left">
            <svg viewBox="0 0 400 300" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
                <!-- Monitor Base -->
                <rect x="80" y="80" width="240" height="150" rx="15" fill="#1a428a" />
                <rect x="90" y="90" width="220" height="130" fill="#ffffff" />
                <rect x="160" y="230" width="80" height="10" fill="#1a428a" />
                
                <!-- Registration form inside monitor -->
                <rect x="120" y="105" width="160" height="100" rx="5" fill="#f8f9fa" stroke="#e0e0e0" stroke-width="2"/>
                <circle cx="200" cy="125" r="12" fill="#c4d2e8" />
                <path d="M190 145 Q200 135 210 145" stroke="#1a428a" stroke-width="3" stroke-linecap="round" fill="none"/>
                
                <rect x="135" y="155" width="130" height="6" rx="3" fill="#1a428a" opacity="0.3"/>
                <rect x="135" y="170" width="130" height="6" rx="3" fill="#1a428a" opacity="0.3"/>
                <rect x="135" y="185" width="130" height="6" rx="3" fill="#1a428a" opacity="0.3"/>
                
                <!-- Shield overlay -->
                <path d="M50 160 L100 160 L100 210 Q75 240 50 210 Z" fill="#ffb74d" />
                <path d="M55 165 L95 165 L95 205 Q75 230 55 205 Z" fill="#1a428a" />
                <!-- Plus in shield (Sign up) -->
                <rect x="71" y="175" width="8" height="20" rx="2" fill="#ffffff" />
                <rect x="65" y="181" width="20" height="8" rx="2" fill="#ffffff" />
                
                <!-- Plant (Now Blue) -->
                <path d="M320 220 Q310 180 320 160 Q330 180 320 220" fill="#3b82f6" />
                <path d="M320 220 Q335 190 340 170 Q325 190 320 220" fill="#2563eb" />
                <path d="M320 220 Q305 190 300 170 Q315 190 320 220" fill="#60a5fa" />
                <rect x="305" y="220" width="30" height="20" rx="3" fill="#8c7ae6" />
            </svg>
        </div>
        
        <div class="login-right">
            <h1 class="login-heading">SIGN UP</h1>
            <p class="login-subheading">Welcome,<br>Create a new account</p>

            @if ($errors->any())
                <div class="tr-alert tr-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="nameInput">Full Name</label>
                            <div class="input-wrapper">
                                <input type="text" name="full_name" id="nameInput" class="input-field" placeholder="Enter Full Name" value="{{ old('full_name') }}" required>
                                <i class="bi bi-person input-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="phoneInput">Phone Number</label>
                            <div class="input-wrapper">
                                <input type="tel" name="phone" id="phoneInput" class="input-field" placeholder="Enter Phone" value="{{ old('phone') }}" required>
                                <i class="bi bi-telephone input-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="emailInput">Email ID</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="emailInput" class="input-field" placeholder="Enter Email ID" value="{{ old('email') }}" required>
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="form-label" for="passInput">Password</label>
                            <div class="input-wrapper">
                                <input type="password" name="password" id="passInput" class="input-field" placeholder="Enter Password" required>
                                <i class="bi bi-lock input-icon"></i>
                            </div>
                        </div>
                        <ul class="pass-criteria">
                            <li id="crit-upper"><i class="bi bi-circle"></i> Uppercase Letter</li>
                            <li id="crit-num"><i class="bi bi-circle"></i> Number</li>
                        </ul>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="confInput">Confirm Password</label>
                            <div class="input-wrapper">
                                <input type="password" name="password_confirmation" id="confInput" class="input-field" placeholder="Confirm Password" required>
                                <i class="bi bi-shield-check input-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="login-btn">SIGN UP</button>
                
                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ route('login') }}" style="color: #1a428a; font-size: 13px; font-weight: 700; text-decoration: none;">Already have an account? Login</a>
                </div>
                
                <div style="text-align: center; margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                    <p style="color: #9e9e9e; font-size: 12px; margin-bottom: 10px;">Are you a business?</p>
                    <div style="display: flex; justify-content: center; gap: 15px;">
                        <a href="{{ route('vendor.register') }}" style="color: #2563eb; font-size: 12px; font-weight: 600; text-decoration: none;"><i class="bi bi-shop me-1"></i> Vendor Register</a>
                        <span style="color: #ccc;">|</span>
                        <a href="{{ route('property_owner.register') }}" style="color: #2563eb; font-size: 12px; font-weight: 600; text-decoration: none;"><i class="bi bi-building me-1"></i> Real Estate Agent Register</a>
                    </div>
                </div>
            </form>
        </div>
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
