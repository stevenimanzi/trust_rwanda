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
            <div id="section-profile" class="settings-section mb-5">
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
            <div id="section-security" class="settings-section mb-5" style="display: none;">
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
            <div id="section-general" class="settings-section mb-5" style="display: none;">
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
            <div id="section-branding" class="settings-section mb-5" style="display: none;">
                <div class="card-settings">
                    <h4 class="settings-header"><i class="bi bi-palette"></i> Brand Assets</h4>
                    <form method="POST" action="{{ route('admin.settings.branding') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4 border text-center">
                                    <label class="form-label mb-2 fw-bold text-muted small d-block">System Logo (Main)</label>
                                    <div class="bg-white p-2 rounded-3 shadow-sm d-inline-block mb-3">
                                        <img id="siteLogoPreview" src="{{ !empty($systemSettings['site_logo']) ? asset('assets/uploads/logos/' . $systemSettings['site_logo']) : 'https://placehold.co/150x50/f8fafc/94a3b8?text=LOGO' }}" style="height: 40px; max-width: 100%; object-fit: contain;">
                                    </div>
                                    <div class="position-relative">
                                        <input type="file" name="site_logo" id="site_logo_input" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;" accept="image/*" onchange="previewAsset(this, 'siteLogoPreview')">
                                        <label for="site_logo_input" class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3"><i class="bi bi-upload me-1"></i> Upload Image</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4 border text-center">
                                    <label class="form-label mb-2 fw-bold text-muted small d-block">Favicon (Tab Icon)</label>
                                    <div class="bg-white p-2 rounded-3 shadow-sm d-inline-block mb-3">
                                        <img id="siteFaviconPreview" src="{{ !empty($systemSettings['site_favicon']) ? asset('assets/uploads/logos/' . $systemSettings['site_favicon']) : 'https://placehold.co/32x32/f8fafc/94a3b8?text=ICO' }}" style="height: 32px; width: 32px; object-fit: contain;" class="rounded-circle">
                                    </div>
                                    <div class="position-relative">
                                        <input type="file" name="site_favicon" id="site_favicon_input" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;" accept="image/x-icon,image/png" onchange="previewAsset(this, 'siteFaviconPreview')">
                                        <label for="site_favicon_input" class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3"><i class="bi bi-upload me-1"></i> Upload Icon</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end border-top pt-4">
                            <button type="submit" class="btn-modern border-0">Upload Brand Assets</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="section-system" class="settings-section mb-5" style="display: none;">
                <div class="card-settings mb-4" style="border: 1px solid #fee2e2;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="settings-header text-danger mb-1"><i class="bi bi-exclamation-triangle"></i> Maintenance Protocol</h5>
                            <p class="text-muted small m-0">Lock all customers and vendors out of the platform. Only admins will have access.</p>
                        </div>
                        <div class="form-check form-switch m-0 d-flex align-items-center">
                            <input class="form-check-input fs-4 m-0 me-3" type="checkbox" id="maintNode" {{ $isMaintActive ? 'checked' : '' }}>
                            <span id="maintStatusText" class="fw-bold small {{ $isMaintActive ? 'text-danger' : 'text-success' }}">{{ $isMaintActive ? 'ACTIVE' : 'INACTIVE' }}</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card-settings h-100 mb-0">
                            <h5 class="settings-header mb-2"><i class="bi bi-cpu text-muted"></i> System Diagnostics</h5>
                            <div class="bg-light rounded-3 border p-2">
                                <ul class="list-group list-group-flush bg-transparent">
                                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-bottom px-2 py-1">
                                        <span class="text-muted fw-bold small uppercase">PHP Engine</span>
                                        <span class="fw-bold text-dark small">{{ phpversion() }}</span>
                                    </li>
                                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-bottom px-2 py-1">
                                        <span class="text-muted fw-bold small uppercase">Laravel</span>
                                        <span class="fw-bold text-dark small">{{ app()->version() }}</span>
                                    </li>
                                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-bottom px-2 py-1">
                                        <span class="text-muted fw-bold small uppercase">Environment</span>
                                        <span class="fw-bold text-dark small">{{ env('APP_ENV', 'production') }}</span>
                                    </li>
                                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-2 py-1">
                                        <span class="text-muted fw-bold small uppercase">Timezone</span>
                                        <span class="fw-bold text-dark small">{{ config('app.timezone') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-settings h-100 mb-0">
                            <h5 class="settings-header mb-2"><i class="bi bi-lightning-charge text-warning"></i> Cache Config</h5>
                            <p class="text-muted" style="font-size: 0.75rem;">Clear stale cache files to ensure the system serves the latest assets.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('admin.settings.ops.optimize') }}" class="flex-grow-1">
                                    @csrf <input type="hidden" name="type" value="app">
                                    <button class="btn btn-light border btn-sm fw-bold w-100"><i class="bi bi-hdd-network text-primary me-1"></i> App</button>
                                </form>
                                <form method="POST" action="{{ route('admin.settings.ops.optimize') }}" class="flex-grow-1">
                                    @csrf <input type="hidden" name="type" value="view">
                                    <button class="btn btn-light border btn-sm fw-bold w-100"><i class="bi bi-window-stack text-info me-1"></i> View</button>
                                </form>
                                <form method="POST" action="{{ route('admin.settings.ops.optimize') }}" class="flex-grow-1">
                                    @csrf <input type="hidden" name="type" value="route">
                                    <button class="btn btn-light border btn-sm fw-bold w-100"><i class="bi bi-sign-turn-right text-success me-1"></i> Route</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card-settings h-100 mb-0">
                            <h5 class="settings-header mb-2"><i class="bi bi-folder-symlink text-info"></i> File System</h5>
                            <p class="text-muted" style="font-size: 0.75rem;">Generate symbolic links for public storage (vendors/products).</p>
                            <form method="POST" action="{{ route('admin.settings.ops.storage_link') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm fw-bold w-100 rounded-pill"><i class="bi bi-link-45deg"></i> Link Storage Directory</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-settings h-100 mb-0">
                            <h5 class="settings-header mb-2"><i class="bi bi-bug text-danger"></i> Error Logs</h5>
                            <p class="text-muted" style="font-size: 0.75rem;">Analyze or clear system logs to monitor silent failures.</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.settings.ops.logs.download') }}" class="btn btn-dark btn-sm fw-bold flex-grow-1 rounded-pill"><i class="bi bi-download"></i> Download</a>
                                <form method="POST" action="{{ route('admin.settings.ops.logs.clear') }}" class="flex-grow-1" onsubmit="return confirm('Clear all logs?');">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm fw-bold w-100 rounded-pill"><i class="bi bi-trash3"></i> Purge</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-settings mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="settings-header mb-1"><i class="bi bi-server text-secondary"></i> Database Backup</h5>
                            <p class="text-muted small m-0">Download a complete snapshot of the database.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.settings.backup') }}">
                            @csrf
                            <button type="submit" class="btn btn-dark fw-bold rounded-pill px-4 btn-sm"><i class="bi bi-cloud-arrow-down me-1"></i> Generate SQL</button>
                        </form>
                    </div>
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

    // Handle tab switching
    function switchSection(id) {
        // Prevent default link behavior if called from an inline event (already handled via URL hash)
        
        // Hide all sections
        document.querySelectorAll('.settings-section').forEach(sec => {
            sec.style.display = 'none';
        });
        
        // Show target section
        const target = document.getElementById(id);
        if (target) {
            target.style.display = 'block';
        }
        
        // Update active nav link
        document.querySelectorAll('#settingsTabs .nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + id) {
                link.classList.add('active');
            }
        });
        
        // Update URL hash without jumping
        history.replaceState(null, null, '#' + id);
    }
    
    // Check hash on load
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.substring(1);
        if (hash && document.getElementById(hash)) {
            switchSection(hash);
        } else {
            switchSection('section-profile');
        }
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
</script>
@endsection

@section('styles')
<style>
    .card-settings { 
        background: var(--hz-surface); 
        border-radius: 20px; 
        border: 1px solid var(--hz-border); 
        padding: 1.5rem; 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02); 
    }
    
    .nav-settings {
        background: white;
        border-radius: 20px;
        padding: 1rem;
        border: 1px solid var(--hz-border);
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.03); 
    }
    .nav-settings .nav-link { 
        color: var(--hz-text-muted); 
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
        background: var(--hz-bg);
        color: var(--hz-primary);
    }
    .nav-settings .nav-link.active { 
        background: var(--hz-primary); 
        color: white; 
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
    }

    .form-label { font-size: 0.75rem; font-weight: 800; color: var(--hz-text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
    
    .form-control-modern { 
        background: var(--hz-bg); 
        color: var(--hz-dark); 
        border: 2px solid transparent; 
        border-radius: 14px; 
        padding: 0.85rem 1.2rem; 
        font-weight: 600; 
        transition: all 0.3s ease;
    }
    .form-control-modern:focus { 
        background: var(--hz-surface); 
        border-color: var(--hz-primary); 
        color: var(--hz-dark); 
        box-shadow: 0 0 0 4px var(--hz-primary-light); 
    }
    
    .btn-modern {
        background: var(--hz-primary);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        background: var(--hz-primary-hover);
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
        color: white;
    }

    .form-switch .form-check-input { width: 3.5em; height: 1.7em; cursor: pointer; background-color: #cbd5e1; border-color: var(--hz-border); }
    .form-switch .form-check-input:checked { background-color: var(--hz-danger); border-color: var(--hz-danger); box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); }

    .settings-header {
        font-weight: 800;
        color: var(--hz-dark);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .settings-header i {
        background: var(--hz-primary-light);
        color: var(--hz-primary);
        padding: 10px;
        border-radius: 12px;
    }

    @media (max-width: 991px) {
        .nav-settings { display: flex; overflow-x: auto; white-space: nowrap; gap: 10px; margin-bottom: 1.5rem; padding: 0.5rem; }
        .nav-settings .nav-link { margin-bottom: 0; font-size: 0.85rem; padding: 0.75rem 1rem; }
    }
</style>
@endsection
