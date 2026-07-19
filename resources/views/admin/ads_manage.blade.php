@extends('layouts.admin')

@section('title', 'Active Campaigns')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-900 m-0 text-dark">Ads & Notifications</h4>
        <p class="text-muted small fw-bold text-uppercase m-0 mt-1" style="letter-spacing: 1px;">Manage promotional content</p>
    </div>
</div>

<!-- Tab Navigation -->
<div class="tabs-nav no-print">
    <button class="tab-btn active" onclick="switchTab('ads_tab', event)"><i class="bi bi-image"></i> Active Ads</button>
    <button class="tab-btn" onclick="switchTab('create_ad_tab', event)"><i class="bi bi-plus-circle"></i> Create Ad</button>
    <button class="tab-btn" onclick="switchTab('notifications_tab', event)"><i class="bi bi-bell"></i> Notifications</button>
</div>

<!-- TAB 1: Active Ads -->
<div id="ads_tab" class="tab-content active">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $ads->count() }}</div>
            <div class="stat-label">Total Ads</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $ads->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $ads->sum('display_count') }}</div>
            <div class="stat-label">Total Views</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $ads->sum('click_count') }}</div>
            <div class="stat-label">Total Clicks</div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($ads as $ad)
        <div class="col-12 col-lg-6">
            <div class="card-admin shadow-sm">
                <div class="d-flex gap-3 mb-3">
                    @if($ad->content_type === 'image')
                        <img src="{{ asset($ad->content_url) }}" class="ad-preview" onerror="this.src='https://placehold.co/120x80?text=No+Image'">
                    @elseif($ad->content_type === 'video')
                        <video class="ad-preview" style="background: #000;"><source src="{{ asset($ad->content_url) }}"></video>
                    @else
                        <div class="ad-preview d-flex align-items-center justify-content-center bg-light border"><i class="bi bi-code-square fs-4" style="color: #6366f1;"></i></div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="fw-900 mb-2 text-dark">{{ $ad->title }}</h6>
                        <div class="mb-2">
                            <span class="badge-placement badge-{{ $ad->content_type ?? 'unknown' }}">{{ strtoupper($ad->content_type ?? 'unknown') }}</span>
                            <span class="badge-placement" style="background: #e2e8f0; color: #475569; margin-left: 0.5rem; border: 1px solid #cbd5e1;">{{ ucfirst(str_replace('_', ' ', $ad->placement ?? 'unknown')) }}</span>
                        </div>
                        <small class="text-muted fw-bold">Status: <span class="text-dark">{{ ucfirst($ad->status) }}</span> • Priority: {{ $ad->priority }}</small>
                    </div>
                </div>

                <div class="row g-2 text-center text-muted fw-bold small mb-3 p-2 bg-light rounded-3 border">
                    <div class="col-6 border-end">👁️ <span class="text-dark fs-6">{{ $ad->display_count }}</span> Views</div>
                    <div class="col-6">🖱️ <span class="text-dark fs-6">{{ $ad->click_count }}</span> Clicks</div>
                </div>

                <div class="d-flex gap-2 no-print">
                    <form method="POST" action="{{ route('admin.ads.update_status') }}" style="flex: 1;">
                        @csrf
                        <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                        <select name="status" class="form-select form-select-sm fw-bold text-dark h-100" onchange="this.form.submit()">
                            <option value="active" {{ $ad->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $ad->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="scheduled" {{ $ad->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                    </form>
                    <form method="POST" action="{{ route('admin.ads.delete') }}" onsubmit="return confirm('Delete this ad?');">
                        @csrf
                        <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                        <button type="submit" class="btn btn-action-admin w-100" style="background: #fff1f2; color: #e11d48; border: 1px solid #ffe4e6;"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- TAB 2: Create Ad -->
<div id="create_ad_tab" class="tab-content">
    <div class="card-admin" style="max-width: 600px;">
        <h6 class="fw-900 mb-4">Create New Advertisement</h6>
        <form method="POST" action="{{ route('admin.ads.create') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-fonts me-1"></i> Ad Title</label>
                <input type="text" name="ad_title" class="form-control" placeholder="e.g., Summer Sale 50% Off" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-text-paragraph me-1"></i> Description</label>
                <textarea name="ad_description" class="form-control" rows="3" placeholder="Brief description of the ad"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-collection-play me-1"></i> Content Type</label>
                <select name="content_type" class="form-select" onchange="toggleContentInputs(this.value)" required>
                    <option value="image">📷 Image Banner</option>
                    <option value="video">🎥 Video</option>
                    <option value="html">💻 Custom HTML</option>
                    <option value="text">📝 Text Only</option>
                </select>
            </div>

            <div id="file_upload_group" class="mb-3">
                <label class="form-label"><i class="bi bi-cloud-upload me-1"></i> Upload Content</label>
                <input type="file" name="content_file" class="form-control" accept="image/*,video/*">
                <small class="text-muted d-block mt-2 fw-medium">Max size: 10MB. Recommended: Image (1200x300px), Video (max 30sec)</small>
            </div>

            <div id="html_content_group" class="mb-3" style="display: none;">
                <label class="form-label"><i class="bi bi-code-square me-1"></i> HTML Content</label>
                <textarea name="html_content" class="form-control" rows="6" placeholder="<div>Your custom HTML here</div>"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-aspect-ratio me-1"></i> Placement</label>
                <select name="placement" class="form-select" required>
                    <option value="banner_top">🎯 Top Banner (Hero Section)</option>
                    <option value="banner_middle">📍 Middle Banner (Content Area)</option>
                    <option value="banner_bottom">⬇️ Bottom Banner (Footer Area)</option>
                    <option value="sidebar">📌 Sidebar Widget</option>
                    <option value="modal_popup">🔔 Modal Popup</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-link-45deg me-1"></i> Target URL (Optional)</label>
                <input type="url" name="target_url" class="form-control" placeholder="https://example.com">
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label"><i class="bi bi-calendar-event me-1"></i> Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-6">
                    <label class="form-label"><i class="bi bi-calendar-event me-1"></i> End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label"><i class="bi bi-sort-numeric-down me-1"></i> Priority (Higher = More Visible)</label>
                <input type="number" name="priority" class="form-control" value="0" min="0" max="100">
            </div>

            <button type="submit" class="btn btn-action-admin btn-primary-admin w-100 py-3"><i class="bi bi-check-circle me-2"></i> Create Advertisement</button>
        </form>
    </div>
</div>

<!-- TAB 3: Push Notifications -->
<div id="notifications_tab" class="tab-content">
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card-admin">
                <h6 class="fw-900 mb-4 text-dark"><i class="bi bi-send-fill text-primary me-2"></i>Send Push Notification</h6>
                <form method="POST" action="{{ route('admin.ads.notify') }}" id="notif_form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-type me-1"></i> Notification Title</label>
                        <input type="text" name="notif_title" class="form-control" placeholder="e.g., Flash Sale Alert!" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-chat-left-text me-1"></i> Message</label>
                        <textarea name="notif_message" class="form-control" rows="4" placeholder="Your notification message" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-people me-1"></i> Target Users</label>
                        <select name="target_users" class="form-select">
                            <option value="all">👥 All Users</option>
                            <option value="customers">🛍️ Customers Only</option>
                            <option value="vendors">🏪 Vendors Only</option>
                            <option value="guests">👤 Guests</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-link-45deg me-1"></i> Action URL (Optional)</label>
                        <input type="url" name="action_url" class="form-control" placeholder="https://example.com/promo">
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-clock me-1"></i> Schedule (Optional)</label>
                        <input type="datetime-local" name="schedule_at" class="form-control">
                        <small class="text-muted d-block mt-1">Leave empty to send immediately</small>
                    </div>

                    <button type="submit" class="btn btn-action-admin btn-primary-admin w-100 py-3"><i class="bi bi-send-fill me-2"></i> Send Notification</button>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card-admin">
                <h6 class="fw-900 mb-4 text-dark"><i class="bi bi-clock-history text-primary me-2"></i>Recent Notifications</h6>
                <div style="max-height: 500px; overflow-y: auto; padding-right: 5px;">
                    @foreach($notifications as $notif)
                    <div class="p-3 mb-3 bg-light rounded-4 border">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold m-0 text-dark">{{ $notif->title }}</h6>
                            @php
                                $statusColor = ($notif->status === 'sent') ? '#10b981' : (($notif->status === 'scheduled') ? '#f59e0b' : '#6b7280');
                            @endphp
                            <span class="badge rounded-pill" style="background: {{ $statusColor }}; font-size: 0.65rem;">
                                {{ ucfirst($notif->status) }}
                            </span>
                        </div>
                        <p class="text-muted small mb-2 fw-medium">{{ substr($notif->message, 0, 80) }}...</p>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <small class="text-muted fw-bold" style="font-size: 0.7rem;">
                                <i class="bi bi-people-fill text-primary me-1"></i> {{ $notif->recipient_count }} Recipients
                            </small>
                            <small class="text-muted fw-bold" style="font-size: 0.7rem;">
                                <i class="bi bi-calendar-check me-1"></i> {{ date('M d, H:i', strtotime($notif->created_at)) }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function switchTab(tabId, event = null) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    if(event) event.currentTarget.classList.add('active');
}

function toggleContentInputs(value) {
    const fileGroup = document.getElementById('file_upload_group');
    const htmlGroup = document.getElementById('html_content_group');
    
    if (value === 'html') {
        fileGroup.style.display = 'none';
        htmlGroup.style.display = 'block';
    } else if (value === 'text') {
        fileGroup.style.display = 'none';
        htmlGroup.style.display = 'none';
    } else {
        fileGroup.style.display = 'block';
        htmlGroup.style.display = 'none';
    }
}
</script>
@endsection

@section('styles')
<style>
    .tabs-nav { display: flex; gap: 1rem; border-bottom: 2px solid var(--border); margin-bottom: 2rem; overflow-x: auto; white-space: nowrap; }
    .tab-btn { background: none; border: none; color: #64748b; padding: 1rem 0.5rem; font-weight: 800; cursor: pointer; border-bottom: 3px solid transparent; transition: 0.3s; margin-bottom: -2px; }
    .tab-btn:hover { color: var(--adm-sidebar-accent); }
    .tab-btn.active { color: var(--adm-sidebar-accent); border-bottom-color: var(--adm-sidebar-accent); }

    .card-admin { background: var(--admin-card); border: 1px solid var(--border); border-radius: 24px; padding: 1.5rem; box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05); }

    .form-control, .form-select { background: #f8fafc; border: 2px solid var(--border); color: var(--admin-text); border-radius: 12px; padding: 0.75rem 1rem; font-weight: 600; }
    .form-control::placeholder { color: #94a3b8; }
    .form-control:focus, .form-select:focus { background: #ffffff; border-color: var(--adm-sidebar-accent); color: var(--admin-text); box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
    .form-label { color: #475569; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }

    .ad-preview { width: 120px; height: 80px; border-radius: 12px; object-fit: cover; background: #f1f5f9; border: 1px solid var(--border); }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    .stat-number { font-size: 2rem; font-weight: 900; color: var(--adm-sidebar-accent); line-height: 1; margin-bottom: 0.5rem; }
    .stat-label { font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }

    .badge-placement { display: inline-block; padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.75rem; font-weight: 800; }
    .badge-image { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .badge-video { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .badge-html { background: rgba(168, 85, 247, 0.1); color: #a855f7; border: 1px solid rgba(168, 85, 247, 0.2); }
    .badge-unknown { background: rgba(107, 114, 128, 0.1); color: #6b7280; border: 1px solid rgba(107, 114, 128, 0.2); }

    .btn-action-admin { border-radius: 12px; font-weight: 800; font-size: 0.85rem; padding: 0.75rem 1.5rem; transition: 0.3s; }
    .btn-primary-admin { background: linear-gradient(135deg, var(--adm-sidebar-accent) 0%, #4338ca 100%); border: none; color: white; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3); }
    .btn-primary-admin:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4); color: white; }

    .tab-content { display: none; animation: fadeIn 0.3s ease; }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
