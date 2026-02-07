@extends('admin.layouts.master')

@section('title', 'Categories')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Categories</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i> Add Category
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Search categories..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="parent" class="form-select">
                    <option value="">All Categories</option>
                    <option value="root" {{ request('parent') === 'root' ? 'selected' : '' }}>Root Categories</option>
                    @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ request('parent') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Categories Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 80px;">Image</th>
                        <th>Category Name</th>
                        <th>Parent</th>
                        <th class="text-center">Products</th>
                        <th class="text-center">Order</th>
                        <th class="text-center">Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td>
                            @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }}"
                                class="rounded"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-folder text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $category->name }}</div>
                            <small class="text-muted">{{ $category->slug }}</small>
                        </td>
                        <td>
                            @if($category->parent)
                            <span class="badge bg-light text-dark">{{ $category->parent->name }}</span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $category->products()->count() }}</span>
                        </td>
                        <td class="text-center">{{ $category->display_order ?? 0 }}</td>
                        <td class="text-center">
                            @if($category->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                                No categories found
                            </div>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary-custom mt-3">
                                <i class="bi bi-plus-lg me-1"></i> Add Your First Category
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($categories->hasPages())
    <div class="card-footer bg-white">
        {{ $categories->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection