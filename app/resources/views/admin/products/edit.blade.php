@extends('admin.layouts.master')

@section('title', 'Edit Product')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Edit {{ $product->name }}</li>
</ol>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Edit Product</h1>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror"
                            id="short_description" name="short_description" rows="2" maxlength="500">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Full Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="men" {{ old('gender', $product->gender) === 'men' ? 'selected' : '' }}>Men</option>
                                <option value="women" {{ old('gender', $product->gender) === 'women' ? 'selected' : '' }}>Women</option>
                                <option value="unisex" {{ old('gender', $product->gender) === 'unisex' ? 'selected' : '' }}>Unisex</option>
                                <option value="kids" {{ old('gender', $product->gender) === 'kids' ? 'selected' : '' }}>Kids</option>
                            </select>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fabric" class="form-label">Fabric</label>
                            <input type="text" class="form-control @error('fabric') is-invalid @enderror"
                                id="fabric" name="fabric" value="{{ old('fabric', $product->fabric) }}" placeholder="e.g., 100% Cotton">
                            @error('fabric')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="care_instructions" class="form-label">Care Instructions</label>
                            <input type="text" class="form-control @error('care_instructions') is-invalid @enderror"
                                id="care_instructions" name="care_instructions" value="{{ old('care_instructions', $product->care_instructions) }}">
                            @error('care_instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Pricing</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Base Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="discount_type" class="form-label">Discount Type</label>
                            <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type">
                                <option value="none" {{ old('discount_type', $product->discount_type) === 'none' ? 'selected' : '' }}>No Discount</option>
                                <option value="percentage" {{ old('discount_type', $product->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ old('discount_type', $product->discount_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                            @error('discount_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="discount_value" class="form-label">Discount Value</label>
                            <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror"
                                id="discount_value" name="discount_value" value="{{ old('discount_value', $product->discount_value) }}">
                            @error('discount_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Images -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Current Images</h5>
                </div>
                <div class="card-body">
                    @if($product->images->count() > 0)
                    <div class="row g-3">
                        @foreach($product->images as $image)
                        <div class="col-4 col-md-3">
                            <div class="position-relative border rounded p-2">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid rounded"
                                    style="height: 100px; width: 100%; object-fit: cover;">
                                @if($image->is_primary)
                                <span class="badge bg-primary position-absolute top-0 start-0 m-2">Primary</span>
                                @endif
                                <div class="mt-2 d-flex gap-1">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="delete_images[]"
                                            value="{{ $image->id }}" id="delete_{{ $image->id }}">
                                        <label class="form-check-label small text-danger" for="delete_{{ $image->id }}">Delete</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">No images uploaded</p>
                    @endif

                    <hr>

                    <label class="form-label">Add New Images</label>
                    <input type="file" class="form-control @error('new_images') is-invalid @enderror @error('new_images.*') is-invalid @enderror"
                        id="new_images" name="new_images[]" accept="image/*" multiple>
                    <div class="form-text">Upload additional images. Max 2MB each.</div>
                    @error('new_images')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Variants -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Product Variants <span class="text-danger">*</span></h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addVariant">
                        <i class="bi bi-plus-lg me-1"></i> Add Variant
                    </button>
                </div>
                <div class="card-body">
                    <div id="variantsContainer">
                        <!-- Existing variants will be loaded here -->
                    </div>
                    @error('variants')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- SEO -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">SEO Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                            id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" maxlength="60">
                        <div class="form-text">Max 60 characters</div>
                        @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                            id="meta_description" name="meta_description" rows="2" maxlength="160">{{ old('meta_description', $product->meta_description) }}</textarea>
                        <div class="form-text">Max 160 characters</div>
                        @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Product Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">SKU:</small>
                        <div class="fw-semibold">{{ $product->sku }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Total Stock:</small>
                        <div class="fw-semibold">{{ $product->total_stock }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Created:</small>
                        <div class="fw-semibold">{{ $product->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div>
                        <small class="text-muted">Updated:</small>
                        <div class="fw-semibold">{{ $product->updated_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Product Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Featured Product</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_new_arrival" name="is_new_arrival" value="1"
                            {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_new_arrival">New Arrival</label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                        <i class="bi bi-check-lg me-1"></i> Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Variants management
    let variantIndex = 0;
    const variantsContainer = document.getElementById('variantsContainer');

    function addVariantRow(data = {}) {
        const row = document.createElement('div');
        row.className = 'variant-row border rounded p-3 mb-3';
        row.innerHTML = `
            <input type="hidden" name="variants[${variantIndex}][id]" value="${data.id || ''}">
            <div class="row g-2">
                <div class="col-md-2">
                    <label class="form-label small">Size *</label>
                    <select class="form-select form-select-sm" name="variants[${variantIndex}][size]" required>
                        <option value="XS" ${data.size === 'XS' ? 'selected' : ''}>XS</option>
                        <option value="S" ${data.size === 'S' ? 'selected' : ''}>S</option>
                        <option value="M" ${data.size === 'M' ? 'selected' : ''}>M</option>
                        <option value="L" ${data.size === 'L' ? 'selected' : ''}>L</option>
                        <option value="XL" ${data.size === 'XL' ? 'selected' : ''}>XL</option>
                        <option value="XXL" ${data.size === 'XXL' ? 'selected' : ''}>XXL</option>
                        <option value="Free" ${data.size === 'Free' ? 'selected' : ''}>Free Size</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Color *</label>
                    <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][color]" 
                           value="${data.color || ''}" placeholder="e.g., Black" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Color Code</label>
                    <input type="color" class="form-control form-control-sm form-control-color" 
                           name="variants[${variantIndex}][color_code]" value="${data.color_code || '#000000'}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Stock *</label>
                    <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][stock_quantity]" 
                           value="${data.stock_quantity ?? 10}" min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Low Stock</label>
                    <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][low_stock_threshold]" 
                           value="${data.low_stock_threshold ?? 5}" min="0">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            ${data.variant_sku ? `<small class="text-muted d-block mt-2">SKU: ${data.variant_sku}</small>` : ''}
        `;

        row.querySelector('.remove-variant').addEventListener('click', function() {
            if (variantsContainer.querySelectorAll('.variant-row').length > 1) {
                row.remove();
            } else {
                alert('At least one variant is required.');
            }
        });

        variantsContainer.appendChild(row);
        variantIndex++;
    }

    document.getElementById('addVariant').addEventListener('click', () => addVariantRow());

    // Load existing variants
    const existingVariants = @json($product - > variants);
    existingVariants.forEach(variant => addVariantRow(variant));

    // If no variants, add one empty
    if (existingVariants.length === 0) {
        addVariantRow({
            size: 'M',
            color: 'Black',
            color_code: '#000000',
            stock_quantity: 10,
            low_stock_threshold: 5
        });
    }
</script>
@endpush