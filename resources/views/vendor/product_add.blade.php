@extends('layouts.vendor')

@section('title', 'Add Product')

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
    
    .upload-zone { 
        border: 2px dashed #cbd5e1; 
        border-radius: 12px; 
        background: #f8fafc; 
        min-height: 250px; 
        display: flex; 
        flex-direction: column;
        align-items: center; 
        justify-content: center; 
        cursor: pointer; 
        transition: all 0.2s;
        padding: 20px;
    }
    .upload-zone:hover { border-color: var(--hz-primary); background: #f3e8ff; }
    .preview-img { max-width: 100%; max-height: 200px; border-radius: 8px; display: none; object-fit: cover; }

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
<div class="container-fluid p-4 p-lg-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark"><i class="bi bi-plus-lg text-primary me-2"></i>Add Product</h2>
            <p class="text-muted m-0 small">Publish a new item to your catalog</p>
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

    <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Upload & Category -->
            <div class="col-lg-4">
                <div class="ecom-card">
                    <div class="chart-title">Featured Media</div>
                    
                    <div class="upload-zone mb-4" onclick="document.getElementById('imgInput').click()">
                        <div id="placeholderText" class="text-center">
                            <i class="bi bi-image text-primary" style="font-size: 2.5rem;"></i>
                            <div class="fw-bold mt-2 small text-muted">Click to Upload Photo</div>
                        </div>
                        <img id="imgPreview" class="preview-img">
                    </div>
                    <input type="file" name="image" id="imgInput" class="d-none" accept="image/*" onchange="handlePreview(this)">

                    <div class="mb-4">
                        <label class="form-label-pro">Market Category</label>
                        <select name="category" class="form-select form-control-pro" required>
                            <option value="">Select Category...</option>
                            @foreach($categories as $catNode)
                                <option value="{{ $catNode->slug }}" {{ old('category') == $catNode->slug ? 'selected' : '' }}>{{ $catNode->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check form-switch p-3 bg-light rounded-3 border-0 d-flex align-items-center" style="background: #f8fafc !important;">
                        <input class="form-check-input m-0 me-3" type="checkbox" name="is_fresh" id="freshCheck" value="1" {{ old('is_fresh') ? 'checked' : '' }} style="cursor:pointer; transform: scale(1.2);">
                        <label class="form-check-label fw-bold small text-dark" for="freshCheck" style="cursor:pointer; user-select:none;">Farm Fresh / Perishable</label>
                    </div>
                </div>
            </div>

            <!-- Right Side: Details forms -->
            <div class="col-lg-8">
                <div class="ecom-card">
                    <div class="chart-title">Listing Details</div>

                    <div class="mb-4">
                        <label class="form-label-pro">Product Title</label>
                        <input type="text" name="title" class="form-control form-control-pro w-100" placeholder="e.g. Premium Basmati Rice 25kg" value="{{ old('title') }}" required>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-5">
                            <label class="form-label-pro">Price (RWF)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0" style="border-radius:8px 0 0 8px; border-color:#e2e8f0; font-weight:600; background:#f8fafc !important;">RWF</span>
                                <input type="number" name="price" class="form-control form-control-pro border-start-0" placeholder="0" style="border-radius:0 8px 8px 0;" min="1" value="{{ old('price') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-pro">Price Unit</label>
                            <select name="price_unit" class="form-select form-control-pro">
                                <option value="">Item (Fixed)</option>
                                <option value="kg" {{ old('price_unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="meter" {{ old('price_unit') == 'meter' ? 'selected' : '' }}>Meter (m)</option>
                                <option value="liter" {{ old('price_unit') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                <option value="bag" {{ old('price_unit') == 'bag' ? 'selected' : '' }}>Bag / Sack</option>
                                <option value="pair" {{ old('price_unit') == 'pair' ? 'selected' : '' }}>Pair</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-pro">Initial Stock</label>
                            <input type="number" name="stock_quantity" class="form-control form-control-pro w-100" min="0" value="{{ old('stock_quantity') }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Rich Description</label>
                        <textarea name="description" class="form-control form-control-pro w-100" rows="6" placeholder="Provide a detailed description of the product to help buyers..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="text-end pt-3 border-top">
                        <button type="submit" class="btn-ecom">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Publish Listing
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
    function handlePreview(input) {
        let pText = document.getElementById('placeholderText');
        let pImg = document.getElementById('imgPreview');
        
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                pImg.src = e.target.result;
                pText.style.display = 'none';
                pImg.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            pText.style.display = 'block';
            pImg.style.display = 'none';
        }
    }
</script>
@endsection
