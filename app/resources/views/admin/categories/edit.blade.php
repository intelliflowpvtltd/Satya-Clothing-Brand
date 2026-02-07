@extends('admin.layouts.master')

@section('title', 'Edit Category')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">Edit {{ $category->name }}</li>
</ol>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Edit Category</h1>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
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
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                            <option value="">None (Root Category)</option>
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Category Image</label>
                        @if($category->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }}"
                                class="rounded"
                                style="max-width: 150px;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                            id="image" name="image" accept="image/*">
                        <div class="form-text">Leave empty to keep current image. Recommended size: 400x400px. Max: 2MB</div>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
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
                            id="meta_title" name="meta_title" value="{{ old('meta_title', $category->meta_title) }}" maxlength="60">
                        <div class="form-text">Max 60 characters</div>
                        @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                            id="meta_description" name="meta_description" rows="2" maxlength="160">{{ old('meta_description', $category->meta_description) }}</textarea>
                        <div class="form-text">Max 160 characters</div>
                        @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                            id="display_order" name="display_order" value="{{ old('display_order', $category->display_order) }}" min="0">
                        @error('display_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Slug:</small>
                        <div class="fw-semibold">{{ $category->slug }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Products:</small>
                        <div class="fw-semibold">{{ $category->products()->count() }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Created:</small>
                        <div class="fw-semibold">{{ $category->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div>
                        <small class="text-muted">Updated:</small>
                        <div class="fw-semibold">{{ $category->updated_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                        <i class="bi bi-check-lg me-1"></i> Update Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection