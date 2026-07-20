@extends('layouts.property_owner')

@section('title', 'Account Settings')

@section('styles')
<style>
    /* ════════ SETTINGS VIEW SYSTEM ════════ */
    .content-grid {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 30px;
        align-items: start;
        margin-top: 25px;
    }
    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
    .settings-sidebar {
        background: white;
        border-radius: 16px;
        padding: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.01);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .settings-nav-btn {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 10px;
        border: none;
        background: transparent;
        color: var(--text-dark);
        font-weight: 600;
        font-size: 0.88rem;
        text-align: left;
        transition: all 0.2s;
        width: 100%;
    }
    .settings-nav-btn:hover {
        background: var(--nav-hover);
        color: var(--primary);
    }
    .settings-nav-btn.active {
        background: var(--primary-light);
        color: var(--primary);
    }
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        margin-bottom: 25px;
    }
    .section-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-control {
        background: #f8fafc;
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        background: white;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .setting-item:last-of-type {
        border-bottom: none;
        margin-bottom: 1.5rem;
    }
    .setting-item-label {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.9rem;
    }
    .setting-item-desc {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 2px;
    }
</style>
@endsection

@section('content')
@php
    $parts = explode(' ', $user->full_name ?? '', 2);
    $firstName = $parts[0] ?? '';
    $lastName = $parts[1] ?? '';
@endphp
<div>
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="page-header-content">
            <h2 class="fw-bold m-0 text-dark"><i class="bi bi-gear-fill text-primary me-2"></i>Account Settings</h2>
            <p class="text-muted m-0 small">Manage your profile, security, and preferences</p>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm rounded-3 mb-4"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger shadow-sm rounded-3 mb-4">
            <i class="bi bi-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content-grid">
        <!-- Sidebar Navigation -->
        <div class="sticky-sidebar">
            <div class="settings-sidebar">
                <button class="settings-nav-btn active" onclick="showSection('profile')">
                    <i class="bi bi-person me-2"></i>Profile
                </button>
                <button class="settings-nav-btn" onclick="showSection('security')">
                    <i class="bi bi-lock me-2"></i>Security
                </button>
                <button class="settings-nav-btn" onclick="showSection('preferences')">
                    <i class="bi bi-bell me-2"></i>Preferences
                </button>
                <button class="settings-nav-btn" onclick="showSection('billing')">
                    <i class="bi bi-credit-card me-2"></i>Billing
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div>
            <!-- Profile Section -->
            <div id="profile-section" class="ecom-card settings-section">
                <div class="section-title">
                    <i class="bi bi-person-circle text-primary"></i>Profile Information
                </div>

                <form method="POST" action="{{ route('property_owner.settings.profile') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control w-100" value="{{ old('first_name', $firstName) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control w-100" value="{{ old('last_name', $lastName) }}">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control w-100" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control w-100" value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Company/Business Name</label>
                        <input type="text" name="company_name" class="form-control w-100" value="{{ old('company_name', $user->shop_name ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio / About</label>
                        <textarea name="bio" class="form-control w-100" rows="4" placeholder="Tell potential buyers about yourself...">{{ old('bio', $user->shop_description ?? '') }}</textarea>
                        <p class="form-text mt-1 text-muted small">Maximum 500 characters</p>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2.5" style="border-radius:10px;"><i class="bi bi-save me-2"></i>Save Changes</button>
                </form>
            </div>

            <!-- Security Section -->
            <div id="security-section" class="ecom-card settings-section" style="display: none;">
                <div class="section-title">
                    <i class="bi bi-shield-lock text-primary"></i>Security & Password
                </div>

                <form method="POST" action="{{ route('property_owner.settings.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control w-100" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control w-100" required>
                            <p class="form-text mt-1 text-muted small">At least 8 characters</p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control w-100" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2.5" style="border-radius:10px;"><i class="bi bi-check-circle me-2"></i>Update Password</button>
                </form>

                <hr class="my-4">

                <div class="section-title text-muted" style="margin-top: 0; font-size: 1rem;">
                    <i class="bi bi-shield-check"></i>Two-Factor Authentication
                </div>
                <p class="text-muted small mb-0">Coming soon: Enable 2FA for enhanced security.</p>
            </div>

            <!-- Preferences Section -->
            <div id="preferences-section" class="ecom-card settings-section" style="display: none;">
                <div class="section-title">
                    <i class="bi bi-bell text-primary"></i>Notification Preferences
                </div>

                <form method="POST" action="{{ route('property_owner.settings.preferences') }}">
                    @csrf
                    @method('PUT')

                    <div class="setting-item">
                        <div>
                            <div class="setting-item-label">Property Inquiries</div>
                            <div class="setting-item-desc">Get notified when someone inquires about your properties</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="notify_inquiries" {{ ($user->notify_inquiries ?? 1) == 1 ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <div class="setting-item-label">Email Notifications</div>
                            <div class="setting-item-desc">Receive important updates via email</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="notify_emails" {{ ($user->notify_emails ?? 1) == 1 ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <div class="setting-item-label">SMS Notifications</div>
                            <div class="setting-item-desc">Receive urgent alerts via SMS</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="notify_sms" {{ ($user->notify_sms ?? 0) == 1 ? 'checked' : '' }}>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2.5" style="border-radius:10px;"><i class="bi bi-save me-2"></i>Save Preferences</button>
                </form>
            </div>

            <!-- Billing Section -->
            <div id="billing-section" class="ecom-card settings-section" style="display: none;">
                <div class="section-title">
                    <i class="bi bi-credit-card text-primary"></i>Billing & Subscriptions
                </div>

                <div class="setting-item">
                    <div>
                        <div class="setting-item-label">Current Status</div>
                        <div class="setting-item-desc">Plan Status: <span class="badge bg-light border text-dark fw-bold px-2.5 py-1.5">{{ ucfirst($user->subscription_status ?? 'pending') }}</span></div>
                    </div>
                </div>

                <p class="text-muted small my-4">Your registered subscription plan status is synced from the verified database. Contact support for plan tier upgrades.</p>

                <button class="btn btn-secondary fw-bold px-4 py-2.5" style="border-radius:10px;" disabled><i class="bi bi-plus me-2"></i>Upgrade Plan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(el => {
        el.style.display = 'none';
    });

    // Remove active class from all buttons
    document.querySelectorAll('.settings-nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected section
    document.getElementById(sectionName + '-section').style.display = 'block';

    // Add active class to clicked button
    event.target.closest('.settings-nav-btn').classList.add('active');
}
</script>
@endsection
