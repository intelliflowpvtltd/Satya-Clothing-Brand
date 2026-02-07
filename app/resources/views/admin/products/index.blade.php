@extends('admin.layouts.master')

@section('title', 'Products')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Products</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Search by name or SKU..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="stock" class="form-select">
                    <option value="">All Stock</option>
                    <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 80px;">Image</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-end">Price</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                        <td>
                            @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                alt="{{ $product->name }}"
                                class="rounded"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                            @if($product->is_featured)
                            <span class="badge bg-warning-subtle text-warning ms-1">Featured</span>
                            @endif
                            @if($product->is_new_arrival)
                            <span class="badge bg-info-subtle text-info ms-1">New</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $product->category->name ?? 'N/A' }}</span>
                        </td>
                        <td class="text-end">
                            @if($product->is_on_sale)
                            <div class="fw-semibold text-success">₹{{ number_format($product->sale_price, 0) }}</div>
                            <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 0) }}</small>
                            <span class="badge bg-danger ms-1">-{{ $product->discount_percentage }}%</span>
                            @else
                            <div class="fw-semibold">₹{{ number_format($product->price, 0) }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            @php $totalStock = $product->total_stock; @endphp
                            @if($totalStock == 0)
                            <span class="badge bg-danger">Out of Stock</span>
                            @elseif($totalStock <= 10)
                                <span class="badge bg-warning">{{ $totalStock }} left</span>
                                @else
                                <span class="badge bg-success">{{ $totalStock }}</span>
                                @endif
                        </td>
                        <td class="text-center">
                            @if($product->status === 'active')
                            <span class="badge bg-success">Active</span>
                            @elseif($product->status === 'inactive')
                            <span class="badge bg-danger">Inactive</span>
                            @else
                            <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                No products found
                            </div>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom mt-3">
                                <i class="bi bi-plus-lg me-1"></i> Add Your First Product
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="card-footer bg-white">
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection