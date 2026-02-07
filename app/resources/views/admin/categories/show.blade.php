@extends('admin.layouts.master')

@section('title', $category->name)

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">{{ $category->name }}</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">{{ $category->name }}</h1>
    <div>
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary-custom">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Category Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                            alt="{{ $category->name }}"
                            class="img-fluid rounded">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-folder fs-1 text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Name:</th>
                                <td>{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <th>Slug:</th>
                                <td><code>{{ $category->slug }}</code></td>
                            </tr>
                            <tr>
                                <th>Parent:</th>
                                <td>{{ $category->parent->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($category->is_active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Display Order:</th>
                                <td>{{ $category->display_order ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Products:</th>
                                <td>{{ $category->products()->count() }}</td>
                            </tr>
                        </table>

                        @if($category->description)
                        <hr>
                        <h6>Description:</h6>
                        <p class="text-muted">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sub-categories -->
        @if($category->children->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Sub-categories ({{ $category->children->count() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->children as $child)
                            <tr>
                                <td>{{ $child->name }}</td>
                                <td>{{ $child->products()->count() }}</td>
                                <td>
                                    @if($child->is_active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.categories.edit', $child) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- SEO -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">SEO Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Meta Title:</small>
                    <div>{{ $category->meta_title ?: '—' }}</div>
                </div>
                <div>
                    <small class="text-muted">Meta Description:</small>
                    <div>{{ $category->meta_description ?: '—' }}</div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Timeline</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Created:</small>
                    <div>{{ $category->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div>
                    <small class="text-muted">Last Updated:</small>
                    <div>{{ $category->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection