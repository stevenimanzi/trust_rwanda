@extends('layouts.property_owner')

@section('title', 'Edit Property')

@push('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        margin-bottom: 25px;
    }
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-label-pro {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-control-pro {
        background: #f8fafc;
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.3s ease;
    }
    .form-control-pro:focus {
        background: white;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    .thumbnail-box {
        width: 100px;
        height: 80px;
        background-size: cover;
        background-position: center;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }
    .thumbnail-delete-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        background: rgba(244, 63, 94, 0.85);
        color: white;
        border: none;
        border-radius: 6px;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        cursor: pointer;
        transition: 0.2s;
    }
    .thumbnail-delete-btn:hover {
        background: #f43f5e;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0" style="max-width: 900px;">
    <!-- Back to listings header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Property</h2>
            <p class="text-muted m-0 small">Update your listing details or upload media assets</p>
        </div>
        <a href="{{ route('property_owner.properties.index') }}" class="btn btn-outline-secondary btn-sm fw-bold d-flex align-items-center gap-1.5 px-3 py-2" style="border-radius:10px;">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
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

    <form method="POST" action="{{ route('property_owner.properties.update', $property->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- 1. Basic Information -->
        <div class="ecom-card">
            <div class="form-section-title">
                <i class="bi bi-info-circle text-primary"></i> Basic Details
            </div>
            
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-md-8 form-group">
                    <label class="form-label-pro">Property Title *</label>
                    <input type="text" name="title" class="form-control-pro form-control w-100" value="{{ old('title', $property->title) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label class="form-label-pro">Listing Status *</label>
                    <select name="status" class="form-control-pro form-control w-100">
                        <option value="available" {{ old('status', $property->status) === 'available' ? 'selected' : '' }}>Active / Available</option>
                        <option value="pending" {{ old('status', $property->status) === 'pending' ? 'selected' : '' }}>Pending Transaction</option>
                        <option value="rented" {{ old('status', $property->status) === 'rented' ? 'selected' : '' }}>Rented out</option>
                        <option value="sold" {{ old('status', $property->status) === 'sold' ? 'selected' : '' }}>Sold</option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label-pro">Description *</label>
                <textarea name="description" class="form-control-pro form-control w-100" rows="5" required>{{ old('description', $property->description) }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6 form-group">
                    <label class="form-label-pro">Listing Type *</label>
                    <select name="listing_type" id="listing_type" class="form-control-pro form-control w-100" onchange="togglePricePeriod()">
                        <option value="sale" {{ old('listing_type', $property->listing_type) === 'sale' ? 'selected' : '' }}>For Sale</option>
                        <option value="rent" {{ old('listing_type', $property->listing_type) === 'rent' ? 'selected' : '' }}>For Rent</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label-pro">Property Type *</label>
                    <select id="property_type" name="property_type" class="form-control-pro form-control w-100" onchange="toggleFeaturesSection()">
                        <option value="house" {{ old('property_type', $property->property_type) === 'house' ? 'selected' : '' }}>House</option>
                        <option value="apartment" {{ old('property_type', $property->property_type) === 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="land" {{ old('property_type', $property->property_type) === 'land' ? 'selected' : '' }}>Land</option>
                        <option value="commercial" {{ old('property_type', $property->property_type) === 'commercial' ? 'selected' : '' }}>Commercial/Office</option>
                    </select>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6 form-group">
                    <label class="form-label-pro">Price (RWF) *</label>
                    <input type="number" name="price" class="form-control-pro form-control w-100" value="{{ old('price', $property->price) }}" min="1" required>
                </div>
                <div class="col-md-6 form-group" id="price_period_wrapper" style="display: {{ old('listing_type', $property->listing_type) === 'rent' ? 'block' : 'none' }};">
                    <label class="form-label-pro">Rental Period</label>
                    <select name="price_period" class="form-control-pro form-control w-100">
                        <option value="month" {{ old('price_period', $property->price_period) === 'monthly' || old('price_period', $property->price_period) === 'month' ? 'selected' : '' }}>Per Month</option>
                        <option value="year" {{ old('price_period', $property->price_period) === 'yearly' || old('price_period', $property->price_period) === 'year' ? 'selected' : '' }}>Per Year</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. Location & Geographics -->
        <div class="ecom-card">
            <div class="form-section-title">
                <i class="bi bi-geo-alt text-primary"></i> Location Specs
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label-pro">Full Address *</label>
                <input type="text" name="address" class="form-control-pro form-control w-100" value="{{ old('address', $property->address) }}" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6 form-group">
                    <label class="form-label-pro">District</label>
                    <input type="text" name="district" class="form-control-pro form-control w-100" value="{{ old('district', $property->district) }}" placeholder="e.g. Nyarugenge">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label-pro">Sector</label>
                    <input type="text" name="sector" class="form-control-pro form-control w-100" value="{{ old('sector', $property->sector) }}" placeholder="e.g. Kiyovu">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6 form-group">
                    <label class="form-label-pro">Latitude (Optional)</label>
                    <input type="number" step="any" name="latitude" id="latitude_input" class="form-control-pro form-control w-100" value="{{ old('latitude', $property->latitude) }}" placeholder="e.g. -1.9441">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label-pro">Longitude (Optional)</label>
                    <input type="number" step="any" name="longitude" id="longitude_input" class="form-control-pro form-control w-100" value="{{ old('longitude', $property->longitude) }}" placeholder="e.g. 30.0619">
                </div>
            </div>

            <div class="form-group mb-0">
                <label class="form-label-pro">Pin Location on Map (Click map or drag marker to set)</label>
                <div id="map-picker" style="height: 250px; border-radius: 12px; border: 1px solid var(--border-color); z-index: 1;"></div>
                <p class="form-text mt-1 text-muted small mb-0">The coordinates above will update automatically when you click the map or drag the pin marker.</p>
            </div>
        </div>

        <!-- 3. Features & Capacity -->
        <div class="ecom-card" id="features_section">
            <div class="form-section-title">
                <i class="bi bi-sliders text-primary"></i> Features & Capacities
            </div>
            
            <div class="row g-3">
                <div class="col-md-4 form-group">
                    <label class="form-label-pro">Bedrooms</label>
                    <input type="number" name="bedrooms" class="form-control-pro form-control w-100" value="{{ old('bedrooms', $features['bedrooms']->feature_value ?? '') }}" min="0">
                </div>
                <div class="col-md-4 form-group">
                    <label class="form-label-pro">Bathrooms</label>
                    <input type="number" name="bathrooms" class="form-control-pro form-control w-100" value="{{ old('bathrooms', $features['bathrooms']->feature_value ?? '') }}" min="0">
                </div>
                <div class="col-md-4 form-group">
                    <label class="form-label-pro">Area Size (Sqm)</label>
                    <input type="number" name="size" class="form-control-pro form-control w-100" value="{{ old('size', $features['size (sqm)']->feature_value ?? '') }}" min="0">
                </div>
            </div>
        </div>

        <!-- 4. Media & Uploads -->
        <div class="ecom-card">
            <div class="form-section-title">
                <i class="bi bi-images text-primary"></i> Media & Uploads
            </div>

            <!-- Existing images list -->
            @if(!$images->isEmpty())
                <div class="mb-4">
                    <label class="form-label-pro d-block mb-3">Current Images</label>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach ($images as $img)
                            @php
                                $localUrl = asset(str_replace('public/', '', $img->image_url));
                            @endphp
                            <div class="thumbnail-box" style="background-image: url('{{ $localUrl }}');">
                                <button type="button" class="thumbnail-delete-btn" onclick="deleteExistingImage({{ $img->id }})">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="form-group mb-3">
                <label class="form-label-pro">Add Property Images (Select Multiple)</label>
                <input type="file" name="images[]" class="form-control form-control-pro w-100" accept="image/*" multiple>
                <p class="form-text mt-1 text-muted small">Choose additional photos. Allowed: JPG, PNG, WEBP.</p>
            </div>

            <div class="form-group">
                <label class="form-label-pro">YouTube Video ID (Optional)</label>
                <input type="text" name="youtube_video_id" class="form-control-pro form-control w-100" value="{{ old('youtube_video_id', $property->youtube_video_id) }}" placeholder="e.g. dQw4w9WgXcQ">
                <p class="form-text mt-1 text-muted small">Input only the ID parameter of the YouTube link (e.g. text after v=).</p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mb-5 text-end">
            <button type="submit" class="btn btn-primary btn-lg fw-bold px-5 py-3" style="border-radius:12px; box-shadow:0 6px 18px rgba(79,70,229,0.3); border:none;">
                <i class="bi bi-save-fill me-2"></i>Update Listing
            </button>
        </div>
    </form>
</div>

<!-- Hidden image deletion form -->
<form id="deleteImageForm" method="POST" action="{{ route('property_owner.properties.delete_image', $property->id) }}" style="display:none;">
    @csrf
    <input type="hidden" name="delete_image_id" id="delete_image_id" value="">
</form>
@endsection

@push('scripts')
<script>
function togglePricePeriod() {
    const listType = document.getElementById('listing_type').value;
    const wrapper = document.getElementById('price_period_wrapper');
    if (listType === 'rent') {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
    }
}

function toggleFeaturesSection() {
    const propType = document.getElementById('property_type').value;
    const featuresSec = document.getElementById('features_section');
    if (propType === 'land') {
        featuresSec.style.display = 'none';
    } else {
        featuresSec.style.display = 'block';
    }
}

function deleteExistingImage(imgId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Are you sure you want to delete this property photo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete_image_id').value = imgId;
            document.getElementById('deleteImageForm').submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Default coordinates: existing property coordinates or Kigali center
    let initialLat = parseFloat(document.getElementById('latitude_input').value) || -1.9441;
    let initialLng = parseFloat(document.getElementById('longitude_input').value) || 30.0619;
    
    const mapPicker = L.map('map-picker').setView([initialLat, initialLng], 14);
    
    // Voyager Carto tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; CartoDB'
    }).addTo(mapPicker);
    
    const marker = L.marker([initialLat, initialLng], {
        draggable: true
    }).addTo(mapPicker);
    
    // Sync on drag
    marker.on('dragend', function(e) {
        const pos = marker.getLatLng();
        document.getElementById('latitude_input').value = pos.lat.toFixed(6);
        document.getElementById('longitude_input').value = pos.lng.toFixed(6);
    });
    
    // Sync on click
    mapPicker.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        document.getElementById('latitude_input').value = lat.toFixed(6);
        document.getElementById('longitude_input').value = lng.toFixed(6);
    });
    
    // Initial toggle
    toggleFeaturesSection();
});
</script>
@endpush
