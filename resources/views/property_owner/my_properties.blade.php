@extends('layouts.property_owner')

@section('title', 'My Properties')

@section('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 20px;
        border: none;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .kpi-title {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .kpi-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 4px;
        line-height: 1.1;
    }
    
    .ecom-table {
        margin: 0;
        white-space: nowrap;
    }
    .ecom-table th {
        background: #f8fafc;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
    }
    .ecom-table td {
        padding: 16px 24px;
        color: #334155;
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .ecom-table tbody tr {
        transition: all 0.2s ease;
    }
    .ecom-table tbody tr:hover {
        background: #f8fafc;
    }
    .table-property-img {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
    }
    
    .status-badge {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .status-badge.status-active {
        background: #ecfdf5;
        color: #10b981;
    }
    .status-badge.status-inactive {
        background: #fff5f5;
        color: #fa5252;
    }
    
    .type-badge {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        background: #f1f5f9;
        color: #475569;
    }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        background: #f8fafc;
        color: #64748b;
        transition: 0.2s;
        text-decoration: none;
    }
    .btn-action-icon:hover {
        background: var(--primary);
        color: white;
    }
    .btn-action-icon.delete:hover {
        background: #fa5252;
        color: white;
    }

    .filter-btn {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        padding: 8px 18px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        text-decoration: none !important;
        transition: 0.2s;
    }
    .filter-btn:hover, .filter-btn.active {
        background: var(--primary);
        color: white !important;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }
    
    /* Checkbox styling */
    .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 0;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Header with Add Property button -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold m-0 text-dark"><i class="bi bi-houses-fill text-primary me-2"></i>My Properties</h2>
            <p class="text-muted m-0 small">Create, edit, and keep track of your active property listings</p>
        </div>
        <a href="{{ route('property_owner.properties.create') }}" class="btn btn-primary fw-bold d-flex align-items-center gap-2 px-4 py-2.5" style="border-radius:12px; box-shadow:0 4px 12px rgba(79,70,229,0.25); border:none;">
            <i class="bi bi-plus-circle-fill"></i> Add Property
        </a>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm rounded-3 mb-4"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total -->
        <div class="col-6 col-md-4 col-xl">
            <div class="ecom-card" style="padding: 24px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">Total Properties</div>
                        <div class="kpi-value">{{ $stats['total'] }}</div>
                        <div class="kpi-trend text-muted" style="opacity: 0.6;"><i class="bi bi-dash"></i> Lifetime</div>
                    </div>
                    <div class="hz-icon-btn-light" style="background: #eef2ff; color: #4f46e5; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-building fs-5"></i></div>
                </div>
            </div>
        </div>
        <!-- Active -->
        <div class="col-6 col-md-4 col-xl">
            <div class="ecom-card" style="padding: 24px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">Active</div>
                        <div class="kpi-value">{{ $stats['active'] }}</div>
                        <div class="kpi-trend text-muted" style="opacity: 0.6;"><i class="bi bi-check2-all"></i> Verified</div>
                    </div>
                    <div class="hz-icon-btn-light" style="background: #ecfdf5; color: #10b981; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-check-circle fs-5"></i></div>
                </div>
            </div>
        </div>
        <!-- Inactive -->
        <div class="col-6 col-md-4 col-xl">
            <div class="ecom-card" style="padding: 24px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">Inactive</div>
                        <div class="kpi-value">{{ $stats['inactive'] }}</div>
                        <div class="kpi-trend text-muted" style="opacity: 0.6;"><i class="bi bi-pause-circle"></i> Paused</div>
                    </div>
                    <div class="hz-icon-btn-light" style="background: #fffbeb; color: #d97706; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-hourglass-split fs-5"></i></div>
                </div>
            </div>
        </div>
        <!-- For Sale -->
        <div class="col-6 col-md-6 col-xl">
            <div class="ecom-card" style="padding: 24px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">For Sale</div>
                        <div class="kpi-value">{{ $stats['for_sale'] }}</div>
                        <div class="kpi-trend text-muted" style="opacity: 0.6;"><i class="bi bi-tags"></i> Listings</div>
                    </div>
                    <div class="hz-icon-btn-light" style="background: #fdf2f8; color: #db2777; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-tag fs-5"></i></div>
                </div>
            </div>
        </div>
        <!-- For Rent -->
        <div class="col-12 col-md-6 col-xl">
            <div class="ecom-card" style="padding: 24px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-title">For Rent</div>
                        <div class="kpi-value">{{ $stats['for_rent'] }}</div>
                        <div class="kpi-trend text-muted" style="opacity: 0.6;"><i class="bi bi-key"></i> Listings</div>
                    </div>
                    <div class="hz-icon-btn-light" style="background: #f0fdfa; color: #0d9488; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-house-door fs-5"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Properties Table Card -->
    <div class="ecom-card p-0 overflow-hidden mb-5">
        <!-- Top Toolbar -->
        <div class="p-4 border-bottom d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 bg-white">
            <div class="d-flex gap-3 flex-wrap align-items-center">
                <!-- Bulk Actions -->
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger fw-bold d-none me-2 shadow-sm rounded-pill px-3 py-2" onclick="submitBulkDelete()">
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
                
                <!-- Category Segmented Control -->
                <div class="bg-light p-1 rounded-pill d-inline-flex border shadow-sm">
                    <a href="?type=all&status={{ urlencode($filterStatus) }}&q={{ urlencode($searchQuery) }}" class="text-decoration-none px-3 py-1 rounded-pill fw-bold {{ ($filterType === 'all') ? 'bg-white text-primary shadow-sm' : 'text-muted' }}" style="font-size: 0.85rem; transition: 0.2s;">All Categories</a>
                    <a href="?type=house&status={{ urlencode($filterStatus) }}&q={{ urlencode($searchQuery) }}" class="text-decoration-none px-3 py-1 rounded-pill fw-bold {{ ($filterType === 'house') ? 'bg-white text-primary shadow-sm' : 'text-muted' }}" style="font-size: 0.85rem; transition: 0.2s;">Houses</a>
                    <a href="?type=apartment&status={{ urlencode($filterStatus) }}&q={{ urlencode($searchQuery) }}" class="text-decoration-none px-3 py-1 rounded-pill fw-bold {{ ($filterType === 'apartment') ? 'bg-white text-primary shadow-sm' : 'text-muted' }}" style="font-size: 0.85rem; transition: 0.2s;">Apartments</a>
                    <a href="?type=land&status={{ urlencode($filterStatus) }}&q={{ urlencode($searchQuery) }}" class="text-decoration-none px-3 py-1 rounded-pill fw-bold {{ ($filterType === 'land') ? 'bg-white text-primary shadow-sm' : 'text-muted' }}" style="font-size: 0.85rem; transition: 0.2s;">Land</a>
                </div>
            </div>
            
            <div class="d-flex gap-3 flex-wrap align-items-center">
                <!-- Status Segmented Control -->
                <div class="bg-light p-1 rounded-pill d-inline-flex border shadow-sm">
                    <a href="?type={{ urlencode($filterType) }}&status=all&q={{ urlencode($searchQuery) }}" class="text-decoration-none px-3 py-1 rounded-pill fw-bold {{ ($filterStatus === 'all') ? 'bg-white text-dark shadow-sm' : 'text-muted' }}" style="font-size: 0.85rem; transition: 0.2s;">All</a>
                    <a href="?type={{ urlencode($filterType) }}&status=active&q={{ urlencode($searchQuery) }}" class="text-decoration-none px-3 py-1 rounded-pill fw-bold {{ ($filterStatus === 'active') ? 'bg-white text-success shadow-sm' : 'text-muted' }}" style="font-size: 0.85rem; transition: 0.2s;"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Active</a>
                    <a href="?type={{ urlencode($filterType) }}&status=inactive&q={{ urlencode($searchQuery) }}" class="text-decoration-none px-3 py-1 rounded-pill fw-bold {{ ($filterStatus === 'inactive') ? 'bg-white text-danger shadow-sm' : 'text-muted' }}" style="font-size: 0.85rem; transition: 0.2s;"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Inactive</a>
                </div>

                <!-- Search input form -->
                <form method="GET" action="" class="d-flex align-items-center gap-2 m-0" style="min-width: 260px;">
                    <input type="hidden" name="type" value="{{ $filterType }}">
                    <input type="hidden" name="status" value="{{ $filterStatus }}">
                    <div class="input-group shadow-sm" style="border-radius: 50px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc;">
                        <span class="input-group-text bg-transparent border-0 text-muted ps-3 pe-2"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control bg-transparent border-0 py-2" placeholder="Search properties..." value="{{ $searchQuery }}" style="font-size:0.85rem; font-weight:600; box-shadow: none;">
                        @if(!empty($searchQuery))
                            <a href="?type={{ urlencode($filterType) }}&status={{ urlencode($filterStatus) }}" class="btn bg-transparent border-0 text-muted"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive bg-white">
            <form id="bulkDeleteForm" method="POST" action="{{ route('property_owner.properties.bulk_destroy') }}">
                @csrf
                <table class="table ecom-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 40px;" class="ps-4">
                                <input class="form-check-input shadow-sm" type="checkbox" id="selectAll">
                            </th>
                            <th>Property</th>
                            <th>Location</th>
                            <th>Type & Status</th>
                            <th>Price</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($properties as $prop)
                            @php
                                $firstImage = $prop->images->sortBy('sort_order')->first();
                                $imageUrl = $firstImage ? asset(str_replace('public/', '', $firstImage->image_url)) : 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80';
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <input class="form-check-input property-checkbox shadow-sm" type="checkbox" name="property_ids[]" value="{{ $prop->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $imageUrl }}" class="table-property-img shadow-sm" alt="Thumbnail">
                                        <div>
                                            <div class="fw-bold text-dark mb-1" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $prop->title }}">{{ $prop->title }}</div>
                                            <div class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ $prop->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark fw-bold">{{ $prop->district }}</div>
                                    <div class="text-muted small">{{ $prop->sector }}</div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center mb-1">
                                        <span class="type-badge">{{ ucfirst($prop->property_type) }}</span>
                                        <span class="type-badge" style="background: {{ $prop->listing_type === 'rent' ? '#dcfce7' : '#e0e7ff' }}; color: {{ $prop->listing_type === 'rent' ? '#166534' : '#3730a3' }};">
                                            For {{ ucfirst($prop->listing_type) }}
                                        </span>
                                    </div>
                                    <span class="status-badge {{ ($prop->status === 'available') ? 'status-active' : 'status-inactive' }}">
                                        {{ ($prop->status === 'available') ? 'Active' : ucfirst($prop->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-primary fw-bold" style="font-size: 1.05rem;">
                                        {{ number_format($prop->price, 0) }} RWF
                                    </div>
                                    <div class="text-muted small fw-bold">
                                        @if($prop->listing_type === 'rent')
                                            /{{ $prop->price_period === 'yearly' ? 'year' : 'month' }}
                                        @else
                                            One-time
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="/properties/{{ $prop->id }}" class="btn-action-icon" target="_blank" title="View Property">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('property_owner.properties.edit', $prop->id) }}" class="btn-action-icon" title="Edit Property">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn-action-icon delete" title="Delete Property" onclick="deleteSingleProperty({{ $prop->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-3"><i class="bi bi-inbox fs-1 text-gray-300" style="font-size: 3rem !important; opacity: 0.5;"></i></div>
                                    <h5 class="fw-bold text-dark mb-1">No properties found</h5>
                                    <p class="text-muted small mb-3">You don't have any properties matching your criteria.</p>
                                    <a href="{{ route('property_owner.properties.create') }}" class="btn btn-primary fw-bold rounded-pill px-4">
                                        <i class="bi bi-plus-circle me-1"></i> Add Property
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<!-- Hidden image deletion form (Single) -->
<form id="deletePropertyForm" method="POST" action="" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    // Handle "Select All" checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    const propertyCheckboxes = document.querySelectorAll('.property-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function updateBulkDeleteButton() {
        const anyChecked = Array.from(propertyCheckboxes).some(cb => cb.checked);
        if (anyChecked) {
            bulkDeleteBtn.classList.remove('d-none');
        } else {
            bulkDeleteBtn.classList.add('d-none');
        }
    }

    if(selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            propertyCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteButton();
        });
    }

    propertyCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = Array.from(propertyCheckboxes).every(c => c.checked);
            selectAllCheckbox.checked = allChecked;
            updateBulkDeleteButton();
        });
    });

    // Handle Bulk Delete Confirmation
    function submitBulkDelete() {
        Swal.fire({
            title: 'Delete Selected Properties?',
            text: 'Are you sure you want to delete the selected properties? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#f43f5e',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }

    // Handle Single Delete Confirmation
    function deleteSingleProperty(propertyId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Are you sure you want to delete this property listing? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#f43f5e',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deletePropertyForm');
                form.action = '/property_owner/properties/' + propertyId;
                form.submit();
            }
        });
    }
</script>
@endpush
