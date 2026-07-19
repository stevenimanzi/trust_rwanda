@extends('layouts.admin')

@section('title', 'Core Configuration')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-900 m-0 text-dark">System Configuration</h3>
        <p class="text-muted mb-0 mt-1">Manage global platform settings and branding</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4 col-xl-3">
        <div class="nav-settings sticky-top" style="top: 100px;">
            <div class="nav flex-column nav-pills w-100" id="settingsTabs">
                <a class="nav-link active" href="#section-profile" onclick="switchSection('section-profile')"><i class="bi bi-person-circle"></i> Profile</a>
                <a class="nav-link" href="#section-security" onclick="switchSection('section-security')"><i class="bi bi-shield-lock-fill"></i> Security</a>
                <a class="nav-link" href="#section-general" onclick="switchSection('section-general')"><i class="bi bi-building-fill"></i> Business Settings</a>
                <a class="nav-link" href="#section-branding" onclick="switchSection('section-branding')"><i class="bi bi-palette-fill"></i> Branding</a>
                <a class="nav-link" href="#section-system" onclick="switchSection('section-system')"><i class="bi bi-cpu-fill"></i> System Ops</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-xl-9">
        <div class="settings-sections">
            <!-- PROFILE TAB -->
            <div id="section-profile" class="settings-section mb-5" style="scroll-margin-top: 100px;">
                <div class="card-settings">
                    <h4 class="settings-header"><i class="bi bi-person-fill-gear"></i> Administrator Profile</h4>
                    <form method="POST" action="{{ route('admin.settings.profile') }}">
                        @csrf
                        <div class="d-flex align-items-center gap-4 mb-5 pb-4 border-bottom">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->full_name) }}&background=4F46E5&color=fff&rounded=true&bold=true&size=100" class="shadow-sm">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $admin->full_name }}</h5>
                                <div class="badge bg-primary-subtle text-primary rounded-pill">Master Administrator</div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <div class="position-relative">
                                    <i class="bi bi-person position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" name="full_name" class="form-control-modern w-100 ps-5" value="{{ $admin->full_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <div class="position-relative">
                                    <i class="bi bi-envelope position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="email" name="email" class="form-control-modern w-100 ps-5" value="{{ $admin->email }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn-modern border-0">Save Profile Details</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SECURITY TAB -->
            <div id="section-security" class="settings-section mb-5" style="scroll-margin-top: 100px;">
                <div class="card-settings">
                    <h4 class="settings-header"><i class="bi bi-shield-lock"></i> Security Credentials</h4>
                    <form method="POST" action="{{ route('admin.settings.password') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Current Password</label>
                            <div class="position-relative">
                                <i class="bi bi-key position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                <input type="password" name="current_pass" class="form-control-modern w-100 ps-5" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <div class="position-relative">
                                    <i class="bi bi-lock position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="password" name="new_pass" class="form-control-modern w-100 ps-5" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <div class="position-relative">
                                    <i class="bi bi-shield-check position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="password" name="confirm_pass" class="form-control-modern w-100 ps-5" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-dark btn-modern border-0">Update Security Keys</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- GENERAL / BUSINESS TAB -->
            <div id="section-general" class="settings-section mb-5" style="scroll-margin-top: 100px;">
                <div class="card-settings">
                    <h4 class="settings-header"><i class="bi bi-buildings"></i> General Business Settings</h4>
                    <form method="POST" action="{{ route('admin.settings.business') }}">
                        @csrf
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Marketplace Name</label>
                                <div class="position-relative">
                                    <i class="bi bi-shop position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" name="site_name" class="form-control-modern w-100 ps-5" value="{{ $systemSettings['site_name'] ?? 'Trust Rwanda' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Default Currency</label>
                                <div class="position-relative">
                                    <i class="bi bi-cash position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" name="currency_code" class="form-control-modern w-100 ps-5" value="{{ $systemSettings['currency_code'] ?? 'RWF' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Platform Commission (%)</label>
                                <div class="position-relative">
                                    <i class="bi bi-percent position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="number" step="0.1" min="0" max="100" name="commission_percent" class="form-control-modern w-100 ps-5" value="{{ $systemSettings['commission_percent'] ?? '0' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Support Email</label>
                                <div class="position-relative">
                                    <i class="bi bi-headset position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="email" name="support_email" class="form-control-modern w-100 ps-5" value="{{ $systemSettings['support_email'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Support Phone</label>
                                <div class="position-relative">
                                    <i class="bi bi-telephone position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" name="support_phone" class="form-control-modern w-100 ps-5" value="{{ $systemSettings['support_phone'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">SEO Meta Description</label>
                                <div class="position-relative">
                                    <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" name="meta_description" class="form-control-modern w-100 ps-5" value="{{ $systemSettings['meta_description'] ?? '' }}" placeholder="Brief summary of your site for search engines">
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-light p-4 rounded-4 border mb-4">
                            <div class="form-check form-switch d-flex align-items-center gap-3 m-0">
                                <input class="form-check-input fs-4" type="checkbox" id="vendorAutoApproval" name="vendor_auto_approval" {{ ($systemSettings['vendor_auto_approval'] ?? '0') === '1' ? 'checked' : '' }}>
                                <div>
                                    <label class="form-check-label fw-bold text-dark d-block" for="vendorAutoApproval">Auto-Approve New Vendors</label>
                                    <span class="small text-muted">When enabled, newly registered vendors can list products immediately.</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn-modern border-0">Save Business Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- BRANDING TAB -->
            <div id="section-branding" class="settings-section mb-5" style="scroll-margin-top: 100px;">
                <div class="card-settings">
                    <h4 class="settings-header"><i class="bi bi-palette"></i> Brand Assets</h4>
                    <form method="POST" action="{{ route('admin.settings.branding') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-5 mb-4">
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 border h-100 text-center">
                                    <label class="form-label mb-3 d-block">System Logo (Main)</label>
                                    <div class="bg-white p-3 rounded-3 shadow-sm d-inline-block mb-4">
                                        <img id="siteLogoPreview" src="{{ kura_logo_image_url($systemSettings['site_logo'] ?? null, 'https://placehold.co/150x50?text=LOGO') }}" style="height: 50px; max-width: 100%; object-fit: contain;">
                                    </div>
                                    <input type="file" name="site_logo" class="form-control-modern w-100" accept="image/*" onchange="previewAsset(this, 'siteLogoPreview')">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 border h-100 text-center">
                                    <label class="form-label mb-3 d-block">Favicon (Browser Tab Icon)</label>
                                    <div class="bg-white p-3 rounded-3 shadow-sm d-inline-block mb-4">
                                        <img id="siteFaviconPreview" src="{{ kura_logo_image_url($systemSettings['site_favicon'] ?? null, 'https://placehold.co/32x32?text=ICO') }}" style="height: 32px; width: 32px; object-fit: contain;" class="rounded-circle shadow-sm">
                                    </div>
                                    <input type="file" name="site_favicon" class="form-control-modern w-100" accept="image/x-icon,image/png" onchange="previewAsset(this, 'siteFaviconPreview')">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end border-top pt-4">
                            <button type="submit" class="btn-modern border-0">Upload Brand Assets</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SYSTEM OPS TAB -->
            <div id="section-system" class="settings-section mb-5" style="scroll-margin-top: 100px;">
                <div class="card-settings mb-4" style="border: 2px solid #fee2e2;">
                    <h4 class="settings-header text-danger"><i class="bi bi-exclamation-triangle"></i> Maintenance Protocol</h4>
                    <p class="text-muted mb-4">Activating maintenance mode will lock all customers and vendors out of the platform. Only administrators will have access.</p>
                    
                    <div class="p-4 rounded-4 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                        <div class="form-check form-switch d-flex align-items-center gap-4 m-0">
                            <input class="form-check-input fs-3" type="checkbox" id="maintNode" {{ $isMaintActive ? 'checked' : '' }}>
                            <div>
                                <label class="form-check-label fw-bold text-danger fs-5 mb-1" for="maintNode">Maintenance Gatekeeper</label>
                                <p class="small text-danger opacity-75 m-0">Currently: <span id="maintStatusText" class="fw-bold">{{ $isMaintActive ? 'ACTIVE (Platform Locked)' : 'INACTIVE (Platform Live)' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-settings">
                    <h4 class="settings-header"><i class="bi bi-database"></i> Database Management</h4>
                    <p class="text-muted mb-4">Download a complete snapshot of the database. Keep this file secure as it contains all user and transaction data.</p>
                    
                    <form method="POST" action="{{ route('admin.settings.backup') }}" class="bg-light p-4 rounded-4 border text-center">
                        @csrf
                        <i class="bi bi-server display-4 text-primary opacity-50 mb-3 d-block"></i>
                        <h6 class="fw-bold mb-3">System SQL Snapshot</h6>
                        <button type="submit" class="btn btn-dark btn-modern border-0"><i class="bi bi-cloud-arrow-down me-2"></i> Generate Full Backup</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Functional AJAX for Maintenance mode toggling
    const maintNode = document.getElementById('maintNode');
    if (maintNode) {
        maintNode.addEventListener('change', function() {
            const isChecked = this.checked;
            const formData = new FormData();
            formData.append('maint_status', isChecked);
            formData.append('_token', '{{ csrf_token() }}');

            const statusText = document.getElementById('maintStatusText');
            statusText.innerText = 'Updating...';

            fetch('{{ route("admin.settings.maintenance") }}', { 
                method: 'POST', 
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    if (isChecked) {
                        statusText.innerText = "ACTIVE (Platform Locked)";
                        statusText.classList.remove('text-success');
                        statusText.classList.add('text-danger');
                    } else {
                        statusText.innerText = "INACTIVE (Platform Live)";
                        statusText.classList.remove('text-danger');
                        statusText.classList.add('text-success');
                    }
                }
            }).catch(err => {
                alert("COMMUNICATION ERROR: Settings not saved.");
                this.checked = !isChecked; // Revert switch on error
                statusText.innerText = "Error - Reverted";
            });
        });
    }

    // Scroll highlighter for active section
    window.addEventListener('scroll', function() {
        let current = 'section-profile';
        const sections = document.querySelectorAll('.settings-section');
        const navLinks = document.querySelectorAll('#settingsTabs .nav-link');
        
        sections.forEach(sec => {
            if (window.scrollY >= (sec.offsetTop - 180)) {
                current = sec.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });

    // Preview uploaded brand asset
    function previewAsset(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function switchSection(id) {
        const el = document.getElementById(id);
        if (el) {
            window.scrollTo({
                top: el.offsetTop - 100,
                behavior: 'smooth'
            });
        }
    }
</script>
@endsection

@section('styles')
<style>
    .card-settings { 
        background: var(--adm-card); 
        border-radius: 24px; 
        border: 1px solid var(--border); 
        padding: 2.5rem; 
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05); 
    }
    
    .nav-settings {
        background: white;
        border-radius: 20px;
        padding: 1rem;
        border: 1px solid var(--border);
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.03); 
    }
    .nav-settings .nav-link { 
        color: #64748b; 
        font-weight: 700; 
        padding: 1rem 1.25rem; 
        border-radius: 14px; 
        margin-bottom: 0.5rem; 
        display: flex; 
        align-items: center; 
        gap: 12px; 
        transition: all 0.3s ease;
    }
    .nav-settings .nav-link i { font-size: 1.2rem; }
    .nav-settings .nav-link:hover {
        background: #f1f5f9;
        color: var(--adm-accent);
    }
    .nav-settings .nav-link.active { 
        background: linear-gradient(135deg, var(--adm-accent) 0%, #3730a3 100%); 
        color: white; 
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
    }

    .form-label { font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
    
    .form-control-modern { 
        background: #f8fafc; 
        color: var(--adm-text); 
        border: 2px solid var(--border); 
        border-radius: 14px; 
        padding: 0.85rem 1.2rem; 
        font-weight: 600; 
        transition: all 0.3s ease;
    }
    .form-control-modern:focus { 
        background: #ffffff; 
        border-color: var(--adm-accent); 
        color: var(--adm-text); 
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); 
    }
    
    .btn-modern {
        background: linear-gradient(135deg, var(--adm-accent) 0%, #3730a3 100%);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
        color: white;
    }

    .form-switch .form-check-input { width: 3.5em; height: 1.7em; cursor: pointer; background-color: #cbd5e1; border-color: var(--border); }
    .form-switch .form-check-input:checked { background-color: #ef4444; border-color: #ef4444; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); }

    .settings-header {
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .settings-header i {
        background: rgba(79, 70, 229, 0.1);
        color: var(--adm-accent);
        padding: 10px;
        border-radius: 12px;
    }

    @media (max-width: 991px) {
        .nav-settings { display: flex; overflow-x: auto; white-space: nowrap; gap: 10px; margin-bottom: 1.5rem; padding: 0.5rem; }
        .nav-settings .nav-link { margin-bottom: 0; font-size: 0.85rem; padding: 0.75rem 1rem; }
    }
</style>
@endsection
