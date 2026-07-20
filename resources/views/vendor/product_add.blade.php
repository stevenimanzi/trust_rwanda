@extends('layouts.vendor')

@section('title', 'Add Product')

@section('styles')
<style>
    .upload-zone { 
        border: 2px dashed #cbd5e1; 
        border-radius: 16px; 
        background: #f8fafc; 
        min-height: 280px; 
        display: flex; 
        flex-direction: column;
        align-items: center; 
        justify-content: center; 
        cursor: pointer; 
        transition: all 0.3s ease;
        padding: 30px;
        position: relative;
        overflow: hidden;
    }
    .upload-zone:hover { 
        border-color: var(--hz-primary, #4f46e5); 
        background: #f0f4ff; 
    }
    .preview-img { 
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        display: none; 
        z-index: 5;
    }
    .form-label-custom {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('content')
<div class="w-100">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;"><i class="bi bi-box-seam text-primary me-2"></i>Add Product</h2>
            <p class="text-muted m-0 mt-1">Publish a new item to your catalog and start selling</p>
        </div>
        <a href="{{ route('vendor.products') }}" class="btn btn-light fw-bold px-4 py-2 border shadow-sm" style="border-radius:8px;">
            <i class="bi bi-x-lg me-1" style="font-size: 0.8rem;"></i> Cancel
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded-4 mb-4 border-0 d-flex align-items-center" style="background: #fff1f2; color: #e11d48; padding: 16px 20px;">
            <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
            <div>
                <div class="fw-bold mb-1">Please fix the following errors:</div>
                <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('msg'))
        <div class="alert alert-success shadow-sm rounded-4 mb-4 border-0" style="background: #ecfdf5; color: #059669; padding: 16px 20px;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i><span class="fw-bold">{{ session('msg') }}</span>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger shadow-sm rounded-4 mb-4 border-0" style="background: #fff1f2; color: #e11d48; padding: 16px 20px;">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i><span class="fw-bold">{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Upload & Category -->
            <div class="col-lg-4">
                <div class="hz-card p-4 rounded-4 shadow-sm border-0 mb-4 h-100">
                    <h5 class="fw-bold mb-4" style="color: #1e293b;">Media & Classification</h5>
                    
                    <div class="upload-zone mb-4" onclick="document.getElementById('imgInput').click()">
                        <div id="placeholderText" class="text-center">
                            <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-cloud-arrow-up text-primary fs-3"></i>
                            </div>
                            <div class="fw-bold text-dark">Upload Product Photo</div>
                            <div class="small text-muted mt-1">PNG, JPG or WEBP up to 3MB</div>
                        </div>
                        <img id="imgPreview" class="preview-img">
                    </div>
                    <input type="file" name="image" id="imgInput" class="d-none" accept="image/*" onchange="handlePreview(this)">

                    <div class="mb-3">
                        <label class="form-label-custom text-uppercase">Main Category</label>
                        <select id="mainCategory" class="form-select hz-form-control py-2 fw-bold text-dark" required onchange="handleMainCategoryChange()">
                            <option value="">Select Main Category...</option>
                            <option value="electronics">Electronics & Tech</option>
                            <option value="farmers-market">Farmers Market (Agri)</option>
                            <option value="second-hand">Second-hand Goods</option>
                        </select>
                    </div>

                    <div class="mb-4" id="subCategoryContainer" style="display: none; animation: fadeIn 0.3s ease;">
                        <label class="form-label-custom text-uppercase">Sub Category</label>
                        <select name="category" id="subCategory" class="form-select hz-form-control py-2" required onchange="handleSubCategoryChange()">
                            <option value="">Select Subcategory...</option>
                        </select>
                    </div>

                    <div id="freshToggleContainer" class="form-check form-switch p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between m-0" style="display: none; animation: fadeIn 0.3s ease;">
                        <label class="form-check-label fw-bold small text-dark m-0" for="freshCheck" style="cursor:pointer; user-select:none;">Farm Fresh / Perishable</label>
                        <input class="form-check-input m-0" type="checkbox" name="is_fresh" id="freshCheck" value="1" {{ old('is_fresh') ? 'checked' : '' }} style="cursor:pointer; transform: scale(1.3);">
                    </div>
                </div>
            </div>

            <!-- Right Side: Details forms -->
            <div class="col-lg-8">
                <div class="hz-card p-4 p-md-5 rounded-4 shadow-sm border-0 h-100">
                    <h5 class="fw-bold mb-4" style="color: #1e293b;">Listing Details</h5>

                    <div class="mb-4">
                        <label class="form-label-custom text-uppercase">Product Title</label>
                        <input type="text" name="title" class="form-control hz-form-control py-3 fw-bold" placeholder="e.g. Premium Basmati Rice 25kg" value="{{ old('title') }}" required>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-5">
                            <label class="form-label-custom text-uppercase">Price (RWF)</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0 bg-light fw-bold text-muted px-3">RWF</span>
                                <input type="number" name="price" class="form-control hz-form-control border-start-0 py-3 fw-bold text-dark fs-5" placeholder="0" min="1" value="{{ old('price') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom text-uppercase">Selling Unit</label>
                            <select name="price_unit" class="form-select hz-form-control py-3">
                                <option value="">Item (Fixed)</option>
                                <option value="kg" {{ old('price_unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="meter" {{ old('price_unit') == 'meter' ? 'selected' : '' }}>Meter (m)</option>
                                <option value="liter" {{ old('price_unit') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                <option value="bag" {{ old('price_unit') == 'bag' ? 'selected' : '' }}>Bag / Sack</option>
                                <option value="pair" {{ old('price_unit') == 'pair' ? 'selected' : '' }}>Pair</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom text-uppercase">Initial Stock</label>
                            <input type="number" name="stock_quantity" class="form-control hz-form-control py-3 fw-bold" min="0" value="{{ old('stock_quantity') }}" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label-custom text-uppercase">Rich Description</label>
                        <textarea name="description" class="form-control hz-form-control" rows="6" placeholder="Provide a detailed description of the product to help buyers make a decision..." required style="resize: none;">{{ old('description') }}</textarea>
                    </div>

                    <div class="text-end border-top pt-4">
                        <button type="submit" class="btn btn-primary fw-bold px-5 py-3 shadow-sm" style="border-radius: 12px; font-size: 1.05rem;">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i> Publish Listing
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<script>
    const subCategoriesMap = {
        'electronics': [
            {slug: 'electronics', name: 'All Electronics'},
            {slug: 'mobile-phones', name: 'Smartphones & Tablets'},
            {slug: 'laptops-computers', name: 'Laptops & Computers'},
            {slug: 'tablets', name: 'Tablets & E-readers'},
            {slug: 'smartwatches', name: 'Smartwatches & Wearables'},
            {slug: 'accessories', name: 'Accessories & Headphones'},
            {slug: 'tv-systems', name: 'TV & Home Systems'},
            {slug: 'speakers-audio', name: 'Speakers & Audio'},
            {slug: 'gaming', name: 'Gaming & Consoles'},
            {slug: 'smart-home', name: 'Smart Home Devices'}
        ],
        'second-hand': [
            {slug: 'second-hand', name: 'All Used Goods'},
            {slug: 'used-vehicles', name: 'Used Vehicles & Motos'},
            {slug: 'used-mobile-phones', name: 'Used Mobile Phones'},
            {slug: 'used-laptops', name: 'Used Laptops & Computers'},
            {slug: 'used-televisions', name: 'Used Televisions'},
            {slug: 'used-furniture', name: 'Pre-owned Furniture'},
            {slug: 'used-electronics', name: 'Used Electronics'}
        ],
        'farmers-market': [
            {slug: 'fruits', name: 'Fresh Fruits'},
            {slug: 'vegetables', name: 'Fresh Vegetables'},
            {slug: 'grains', name: 'Cereals & Grains'},
            {slug: 'dairy', name: 'Dairy & Honey'},
            {slug: 'meat', name: 'Meat & Poultry'}
        ]
    };

    function handleMainCategoryChange() {
        const mainCat = document.getElementById('mainCategory').value;
        const subCatContainer = document.getElementById('subCategoryContainer');
        const subCatSelect = document.getElementById('subCategory');
        const freshToggle = document.getElementById('freshToggleContainer');
        const freshCheck = document.getElementById('freshCheck');
        
        // Reset and hide
        subCatSelect.innerHTML = '<option value="">Select Subcategory...</option>';
        freshToggle.style.display = 'none';
        freshCheck.checked = false;

        if (mainCat && subCategoriesMap[mainCat]) {
            subCatContainer.style.display = 'block';
            subCategoriesMap[mainCat].forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.slug;
                opt.textContent = sub.name;
                subCatSelect.appendChild(opt);
            });
        } else {
            subCatContainer.style.display = 'none';
        }
    }

    function handleSubCategoryChange() {
        const subCat = document.getElementById('subCategory').value;
        const freshToggle = document.getElementById('freshToggleContainer');
        const freshCheck = document.getElementById('freshCheck');

        if (subCat === 'fruits' || subCat === 'vegetables') {
            freshToggle.style.display = 'flex';
        } else {
            freshToggle.style.display = 'none';
            freshCheck.checked = false;
        }
    }

    // Run on load to restore state if form validation failed
    document.addEventListener('DOMContentLoaded', () => {
        const oldCategory = "{{ old('category') }}";
        if (oldCategory) {
            // Find which main category holds this subcategory
            let foundMainCat = '';
            for (const [main, subs] of Object.entries(subCategoriesMap)) {
                if (subs.some(s => s.slug === oldCategory)) {
                    foundMainCat = main;
                    break;
                }
            }
            if (foundMainCat) {
                document.getElementById('mainCategory').value = foundMainCat;
                handleMainCategoryChange();
                document.getElementById('subCategory').value = oldCategory;
                handleSubCategoryChange();
            }
        }
    });

    function handlePreview(input) {
        let pText = document.getElementById('placeholderText');
        let pImg = document.getElementById('imgPreview');
        
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                pImg.src = e.target.result;
                pText.style.opacity = '0';
                setTimeout(() => pText.style.display = 'none', 300);
                pImg.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            pText.style.display = 'block';
            setTimeout(() => pText.style.opacity = '1', 50);
            pImg.style.display = 'none';
        }
    }
</script>
@endsection
