@extends('layouts.vendor')

@section('title', 'Product Catalog')

@section('styles')
<style>
    .ecom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
        padding: 20px;
        border: none;
        height: 100%;
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
        padding: 12px 0;
        color: #475569;
        font-weight: 600;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
    }
    
    .status-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .status-active { background: #ecfdf5; color: #10b981; }
    .status-hidden { background: #f1f5f9; color: #64748b; }
    
    .form-control-pro {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--hz-text-main);
        outline: none;
        transition: all 0.2s;
        background: var(--hz-bg);
    }
    .form-control-pro:focus {
        border-color: var(--hz-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        background: #fff;
    }
    
    .btn-ecom {
        background: var(--hz-primary);
        color: white;
        border-radius: 8px;
        padding: 8px 16px;
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
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="chart-title fs-4 mb-0">Product Catalog</div>
    <a href="{{ route('vendor.products.create') }}" class="btn-ecom d-flex align-items-center gap-2 text-decoration-none">
        <i class="bi bi-plus-lg"></i> Add Product
    </a>
</div>

@if (session('msg'))
    <div class="alert alert-success shadow-sm rounded-3 mb-4 border-0" style="background: #ecfdf5; color: #10b981;"><i class="bi bi-check-circle me-2"></i>{{ session('msg') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger shadow-sm rounded-3 mb-4 border-0" style="background: #fff1f2; color: #f43f5e;"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="ecom-card mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <form action="{{ route('vendor.products') }}" method="GET" class="d-flex m-0 w-100" id="searchForm" style="max-width: 350px;">
            <div class="position-relative w-100">
                <i class="bi bi-search position-absolute text-muted" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                <input type="text" name="q" class="form-control-pro w-100 ps-5" placeholder="Search by name or category" value="{{ $search }}" id="searchInput">
            </div>
        </form>
        <button type="submit" form="bulkDeleteForm" class="btn btn-sm fw-bold d-none align-items-center gap-2" id="bulkDeleteBtn" style="background: #fff1f2; color: #f43f5e; border: 1px solid #fecdd3; border-radius: 8px; padding: 8px 16px;">
            <i class="bi bi-trash-fill"></i> Delete Selected
        </button>
    </div>

    <form action="{{ route('vendor.products.bulk_delete') }}" method="POST" id="bulkDeleteForm" onsubmit="return confirm('Are you sure you want to permanently delete all selected products?');">
        @csrf
        <div class="table-responsive">
            <table class="ecom-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input class="form-check-input" type="checkbox" id="selectAll" style="cursor: pointer;">
                        </th>
                        <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                @if($products->isEmpty())
                    <tr><td colspan="7" class="text-center py-5 text-muted">No products found in your catalog.</td></tr>
                @else
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <input class="form-check-input row-checkbox" type="checkbox" name="product_ids[]" value="{{ $product->id }}" style="cursor: pointer;">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ kura_product_image_url($product->image_url, 'https://placehold.co/40x40/f1f5f9/94a3b8?text=Img') }}" 
                                     style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover; border: 1px solid #f1f5f9;">
                                <div>
                                    <div class="fw-bold text-dark">{{ $product->title }}</div>
                                    @if($product->is_fresh_produce)
                                        <div style="font-size: 0.7rem; color: #10b981;"><i class="bi bi-leaf-fill"></i> Fresh Produce</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category }}</td>
                        <td>RWF {{ number_format($product->price) }} / {{ $product->price_unit }}</td>
                        <td>
                            <div class="fw-bold {{ $product->stock_quantity < 5 ? 'text-danger' : 'text-dark' }}">
                                {{ $product->stock_quantity }}
                            </div>
                        </td>
                        <td>
                            <span class="status-pill {{ $product->is_visible ? 'status-active' : 'status-hidden' }}">
                                {{ $product->is_visible ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-sm btn-light border-0 me-1" style="background: #f8fafc; color: #64748b;"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('vendor.products.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product permanently?');">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-sm btn-light border-0" style="background: #fff1f2; color: #f43f5e;"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    let typingTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function() {
            document.getElementById('searchForm').submit();
        }, 500);
    });

    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function toggleBulkDeleteButton() {
        const anyChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
        if (anyChecked) {
            bulkDeleteBtn.classList.remove('d-none');
            bulkDeleteBtn.classList.add('d-flex');
        } else {
            bulkDeleteBtn.classList.add('d-none');
            bulkDeleteBtn.classList.remove('d-flex');
        }
    }

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDeleteButton();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = Array.from(rowCheckboxes).every(c => c.checked);
            selectAll.checked = allChecked;
            toggleBulkDeleteButton();
        });
    });
</script>
@endsection
