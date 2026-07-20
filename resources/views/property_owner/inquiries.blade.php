@extends('layouts.property_owner')

@section('title', 'Direct Inquiries')

@push('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 20px;
        border: none;
        margin-top: 25px;
    }
    .ecom-table {
        width: 100%;
        font-size: 0.85rem;
    }
    .ecom-table th {
        color: #1e293b;
        font-weight: 700;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
    .ecom-table td {
        padding: 16px 0;
        color: #475569;
        font-weight: 600;
        vertical-align: top;
        border-bottom: 1px solid #f8fafc;
    }
    .ecom-table tbody tr:last-child td {
        border-bottom: none;
    }
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 50px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .empty-icon {
        font-size: 3rem;
        color: var(--text-muted);
        margin-bottom: 15px;
    }
    .empty-text {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 5px;
    }
    .empty-subtext {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 20px;
        font-weight: 600;
    }
    .btn-edit {
        background: #eff6ff;
        color: var(--primary);
        border: none;
        font-weight: 700;
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 8px 12px;
        transition: 0.2s;
    }
    .btn-edit:hover {
        background: var(--primary);
        color: white;
    }
    .btn-delete {
        background: #fff5f5;
        color: #fa5252;
        border: none;
        font-weight: 700;
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 8px 12px;
        transition: 0.2s;
    }
    .btn-delete:hover {
        background: #fa5252;
        color: white;
    }
</style>
@endpush

@section('content')
<div>
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="page-header-content">
            <h2 class="dashboard-title m-0 fw-bold"><i class="bi bi-chat-dots-fill me-2 text-primary"></i>Direct Inquiries</h2>
            <p class="text-muted mb-0">View and manage messages sent to your real estate portfolio from buyers</p>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm rounded-3 mb-4"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    @if($inquiries->isEmpty())
        <div class="empty-state py-5">
            <div class="empty-icon"><i class="bi bi-chat-left-text-fill"></i></div>
            <div class="empty-text">No inquiries received yet</div>
            <div class="empty-subtext">Customer inquiry messages from your listings details pages will appear here.</div>
        </div>
    @else
        <div class="ecom-card">
            <div class="table-responsive">
                <table class="ecom-table">
                <thead>
                    <tr>
                        <th>Sender Details</th>
                        <th>Inquiry Message</th>
                        <th>Date Received</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inquiries as $inq)
                        @php
                            $senderName = $inq->sender->full_name ?? 'Anonymous Buyer';
                            $senderEmail = $inq->sender->email ?? 'No email address';
                            $senderPhone = $inq->sender->phone ?? '';
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($senderName) }}&background=eff6ff&color=4F46E5&bold=true" class="rounded-circle" style="width: 36px; height: 36px;">
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $senderName }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">{{ $senderEmail }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">{{ $senderPhone ?: 'No contact phone' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-bold mb-1" style="font-size: 0.85rem;">RE: <a href="{{ route('property.show', $inq->property->id) }}" class="text-primary text-decoration-none hover-underline">{{ $inq->property->title }}</a></div>
                                <div class="text-muted" style="font-size: 0.8rem; line-height: 1.5;">{{ $inq->message }}</div>
                            </td>
                            <td>
                                <span class="text-muted small" style="font-size: 0.78rem; font-weight: 700;">{{ $inq->created_at->format('M d, Y h:i A') }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end align-items-center">
                                    @if(!empty($senderPhone))
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $senderPhone) }}" target="_blank" class="btn btn-sm btn-edit d-flex align-items-center gap-1.5 px-3 py-1.5" style="background:#e8fbf3; color:#10b981;">
                                            <i class="bi bi-whatsapp"></i><span>Chat</span>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('property_owner.inquiries.destroy', $inq->id) }}" onsubmit="return confirm('Are you sure you want to delete this inquiry?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-delete d-flex align-items-center gap-1.5 px-3 py-1.5">
                                            <i class="bi bi-trash"></i><span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
