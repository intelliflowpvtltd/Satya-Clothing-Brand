@extends('admin.layouts.master')

@section('title', $product->name)

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">{{ $product->name }}</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">{{ $product->name }}</h1>
    <div>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary-custom">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Images -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Product Images</h5>
            </div>
            <div class="card-body">
                @if($product->images->count() > 0)
                <div class="row g-3">
                    @foreach($product->images as $image)
                    <div class="col-4 col-md-3">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                alt="{{ $product->name }}"
                                class="img-fluid rounded"
                                style="height: 120px; width: 100%; object-fit: cover;">
                            @if($image->is_primary)
                            <span class="badge bg-primary position-absolute top-0 start-0 m-1">Primary</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No images uploaded</p>
                @endif
            </div>
        </div>

        <!-- Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Product Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">SKU:</th>
                        <td><code>{{ $product->sku }}</code></td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Gender:</th>
                        <td>{{ ucfirst($product->gender) }}</td>
                    </tr>
                    <tr>
                        <th>Fabric:</th>
                        <td>{{ $product->fabric ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Care:</th>
                        <td>{{ $product->care_instructions ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($product->status === 'active')
                            <span class="badge bg-success">Active</span>
                            @elseif($product->status === 'inactive')
                            <span class="badge bg-danger">Inactive</span>
                            @else
                            <span class="badge bg-secondary">Draft</span>
                            @endif
                            @if($product->is_featured)
                            <span class="badge bg-warning ms-1">Featured</span>
                            @endif
                            @if($product->is_new_arrival)
                            <span class="badge bg-info ms-1">New Arrival</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($product->short_description)
                <hr>
                <h6>Short Description:</h6>
                <p class="text-muted">{{ $product->short_description }}</p>
                @endif

                @if($product->description)
                <hr>
                <h6>Full Description:</h6>
                <div class="text-muted">{!! nl2br(e($product->description)) !!}</div>
                @endif
            </div>
        </div>

        <!-- Variants -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Variants ({{ $product->variants->count() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SKU</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th class="text-center">Stock</th>
                                <th class="text-end">Price Adj.</th>
                                <th class="text-end">Final Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $variant)
                            <tr>
                                <td><code>{{ $variant->variant_sku }}</code></td>
                                <td>{{ $variant->size }}</td>
                                <td>
                                    <span class="d-inline-block rounded me-1"
                                        style="width: 16px; height: 16px; background-color: {{ $variant->color_code ?? '#ccc' }}; vertical-align: middle;"></span>
                                    {{ $variant->color }}
                                </td>
                                <td class="text-center">
                                    @if($variant->stock_quantity == 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($variant->is_low_stock)
                                    <span class="badge bg-warning">{{ $variant->stock_quantity }} (Low)</span>
                                    @else
                                    <span class="badge bg-success">{{ $variant->stock_quantity }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($variant->price_adjustment > 0)
                                    <span class="text-success">+₹{{ number_format($variant->price_adjustment, 0) }}</span>
                                    @elseif($variant->price_adjustment < 0)
                                        <span class="text-danger">-₹{{ number_format(abs($variant->price_adjustment), 0) }}</span>
                                        @else
                                        —
                                        @endif
                                </td>
                                <td class="text-end fw-semibold">₹{{ number_format($variant->final_price, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        @if($product->reviews->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Reviews ({{ $product->reviews->count() }})</h5>
            </div>
            <div class="card-body">
                @foreach($product->reviews->take(5) as $review)
                <div class="d-flex mb-3 pb-3 border-bottom">
                    <div class="flex-shrink-0">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                        </div>
                        <div class="text-warning mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                        </div>
                        <p class="mb-0 text-muted">{{ $review->review }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Pricing -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Pricing</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    @if($product->is_on_sale)
                    <div class="fs-2 fw-bold text-success">₹{{ number_format($product->sale_price, 0) }}</div>
                    <div class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 0) }}</div>
                    <span class="badge bg-danger mt-2">{{ $product->discount_percentage }}% OFF</span>
                    @else
                    <div class="fs-2 fw-bold">₹{{ number_format($product->price, 0) }}</div>
                    @endif
                </div>

                @if($product->discount_type !== 'none')
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Discount Type:</span>
                    <span>{{ ucfirst($product->discount_type) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Discount Value:</span>
                    <span>{{ $product->discount_type === 'percentage' ? $product->discount_value . '%' : '₹' . number_format($product->discount_value, 0) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Stock Summary -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Stock Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Stock:</span>
                    <span class="fw-semibold">{{ $product->total_stock }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Variants:</span>
                    <span class="fw-semibold">{{ $product->variants->count() }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">In Stock:</span>
                    <span class="fw-semibold">
                        @if($product->is_in_stock)
                        <span class="text-success">Yes</span>
                        @else
                        <span class="text-danger">No</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">SEO Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Meta Title:</small>
                    <div>{{ $product->meta_title ?: '—' }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Meta Description:</small>
                    <div>{{ $product->meta_description ?: '—' }}</div>
                </div>
                <div>
                    <small class="text-muted">Slug:</small>
                    <div><code>{{ $product->slug }}</code></div>
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
                    <div>{{ $product->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div>
                    <small class="text-muted">Last Updated:</small>
                    <div>{{ $product->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection