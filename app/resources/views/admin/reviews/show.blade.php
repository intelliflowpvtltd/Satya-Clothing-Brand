@extends('admin.layouts.master')

@section('title', 'Review Details')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
    <li class="breadcrumb-item active">Review #{{ $review->id }}</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Review Details</h1>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Review Content -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-gradient rounded-circle d-flex align-items-center justify-content-center text-white me-3"
                            style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $review->user->name ?? 'Guest' }}</h5>
                            <small class="text-muted">{{ $review->user->email ?? '' }}</small>
                            @if($review->is_verified_purchase)
                            <span class="badge bg-success ms-2">Verified Purchase</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="text-warning fs-4">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                        </div>
                        <small class="text-muted">{{ $review->created_at->format('M d, Y h:i A') }}</small>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-5">{{ $review->review }}</p>
                </div>

                @if($review->admin_response)
                <div class="bg-light rounded p-3 mt-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-reply text-primary me-2"></i>
                        <strong>Admin Response</strong>
                        <small class="text-muted ms-2">{{ $review->admin_response_at?->format('M d, Y') }}</small>
                    </div>
                    <p class="mb-0">{{ $review->admin_response }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Respond Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">{{ $review->admin_response ? 'Update Response' : 'Respond to Review' }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Your Response</label>
                        <textarea name="admin_response" class="form-control" rows="4"
                            placeholder="Write your response to this review...">{{ old('admin_response', $review->admin_response) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_approved" value="0">
                            <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1"
                                {{ $review->is_approved ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_approved">Approved</label>
                        </div>

                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-send me-1"></i> Submit Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Product -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Product</h5>
            </div>
            <div class="card-body">
                @if($review->product)
                <div class="text-center mb-3">
                    @if($review->product->primaryImage)
                    <img src="{{ asset('storage/' . $review->product->primaryImage->image_path) }}"
                        class="rounded"
                        style="max-height: 150px;">
                    @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto"
                        style="width: 150px; height: 150px;">
                        <i class="bi bi-image text-muted fs-1"></i>
                    </div>
                    @endif
                </div>
                <h6 class="text-center">{{ $review->product->name }}</h6>
                <div class="text-center">
                    <span class="text-muted">SKU:</span> {{ $review->product->sku }}
                </div>
                <div class="text-center text-primary fw-bold fs-5 mt-2">
                    ₹{{ number_format($review->product->sale_price ?? $review->product->price, 0) }}
                </div>
                <a href="{{ route('admin.products.show', $review->product) }}" class="btn btn-outline-primary w-100 mt-3">
                    View Product
                </a>
                @else
                <p class="text-muted mb-0">Product has been deleted</p>
                @endif
            </div>
        </div>

        <!-- Status -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Status</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Status:</td>
                        <td class="text-end">
                            @if($review->is_approved)
                            <span class="badge bg-success">Approved</span>
                            @else
                            <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Verified:</td>
                        <td class="text-end">
                            @if($review->is_verified_purchase)
                            <span class="text-success"><i class="bi bi-check-circle-fill"></i> Yes</span>
                            @else
                            <span class="text-muted">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created:</td>
                        <td class="text-end">{{ $review->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection