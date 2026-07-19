@extends('layouts.vendor')

@section('title', 'Edit Product #' . $product->id)

@section('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 24px;
        border: none;
        margin-bottom: 24px;
    }
    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--hz-text-main);
        margin-bottom: 20px;
    }
    
    .current-img-box { 
        width: 100%; 
        aspect-ratio: 1/1; 
        border-radius: 12px; 
        overflow: hidden; 
        border: 2px dashed #cbd5e1; 
        background: #f8fafc; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    }
    .current-img-box img { width: 100%; height: 100%; object-fit: cover; }

    .form-label-pro {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-control-pro {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--hz-text-main);
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .form-control-pro:focus {
        border-color: var(--hz-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        background: #ffffff;
    }
    
    .btn-ecom {
        background: var(--hz-primary);
        color: white;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 600;
        border: none;
        transition: opacity 0.2s;
    }
    .btn-ecom:hover {
        opacity: 0.9;
        color: white;
    }
</style>
@endsection

@section('content')
@php
    $img = $product->image_url;
    $displayImage = $img ? asset('assets/uploads/products/' . $img) : 'https://placehold.co/400x400?text=No+Media';
    if ($img && str_starts_with($img, 'http')) {
        $displayImage = $img;
    }
    $units = ['kg' => 'Kilogram', 'bag' => 'Bag', 'meter' => 'Meter', 'liter' => 'Liter', 'pair' => 'Pair', 'box' => 'Box'];
@endphp
<div class="container-fluid p-4 p-lg-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Update Listing</h2>
            <p class="text-muted m-0 small">Editing SKU: <strong>#{{ $product->id }}</strong></p>
        </div>
        <a href="{{ route('vendor.products') }}" class="btn btn-outline-secondary btn-sm fw-bold px-4 py-2" style="border-radius:8px;">
            Cancel
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded-3 mb-4 border-0" style="background: #fff1f2; color: #f43f5e;">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</div>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('msg'))
        <div class="alert alert-success shadow-sm rounded-3 mb-4 border-0" style="background: #ecfdf5; color: #10b981;"><i class="bi bi-check-circle me-2"></i>{{ session('msg') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger shadow-sm rounded-3 mb-4 border-0" style="background: #fff1f2; color: #f43f5e;"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('vendor.products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Uploader & Visibility -->
            <div class="col-lg-4">
                <div class="ecom-card">
                    <div class="chart-title">Featured Image</div>
                    
                    <div class="current-img-box mb-3" onclick="document.getElementById('imgInput').click()" style="cursor: pointer;">
                        <img src="{{ $displayImage }}" id="previewImg" onerror="this.src='https://placehold.co/400x400?text=No+Media'">
                    </div>
                    <input type="file" name="image" id="imgInput" class="d-none" accept="image/*" onchange="previewFile(this)">
                    <div class="text-center small text-muted mb-4 fw-bold">Click image window to swap image</div>

                    <label class="form-label-pro">Visibility Node</label>
                    <div class="form-check form-switch p-3 bg-light rounded-3 border-0 d-flex align-items-center" style="background:#f8fafc !important;">
                        <input class="form-check-input m-0 me-3" type="checkbox" name="is_visible" id="visSwitch" value="1" {{ $product->is_visible ? 'checked' : '' }} style="cursor:pointer; transform:scale(1.2);">
                        <label class="form-check-label fw-bold small text-dark" for="visSwitch" style="cursor:pointer; user-select:none;">Publicly Visible</label>
                    </div>
                </div>
            </div>

            <!-- Right Side: Details Forms -->
            <div class="col-lg-8">
                <div class="ecom-card">
                    <div class="chart-title">Update Specs</div>

                    <div class="mb-4">
                        <label class="form-label-pro">Product Name</label>
                        <input type="text" name="title" class="form-control form-control-pro w-100" value="{{ old('title', $product->title) }}" required>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label-pro">Category</label>
                            <select name="category" class="form-select form-control-pro" required>
                                @foreach($categories as $catNode)
                                    <option value="{{ $catNode->slug }}" {{ old('category', $product->category) == $catNode->slug ? 'selected' : '' }}>{{ $catNode->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-pro">Price (RWF)</label>
                            <input type="number" name="price" class="form-control form-control-pro w-100" value="{{ old('price', $product->price) }}" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-pro">Available Units</label>
                            <input type="number" name="stock_quantity" class="form-control form-control-pro w-100" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Price Per Unit</label>
                        <select name="price_unit" class="form-select form-control-pro">
                            <option value="">Single Item (Standard)</option>
                            @foreach($units as $uVal => $uName)
                                <option value="{{ $uVal }}" {{ old('price_unit', $product->price_unit) == $uVal ? 'selected' : '' }}>{{ $uName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Rich Description</label>
                        <textarea name="description" class="form-control form-control-pro w-100" rows="6" required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="text-end pt-3 border-top">
                        <button type="submit" class="btn-ecom">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Update Listing
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewFile(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
