@extends('admin.layouts.master')

@section('title', 'Reviews')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Reviews</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Reviews</h1>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['total'] }}</div>
                <small class="text-muted">Total Reviews</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-warning">{{ $stats['pending'] }}</div>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-success">{{ $stats['approved'] }}</div>
                <small class="text-muted">Approved</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-info">
                    <i class="bi bi-star-fill text-warning"></i> {{ $stats['avg_rating'] }}
                </div>
                <small class="text-muted">Avg Rating</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Search product or customer..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="rating" class="form-select">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="verified" class="form-select">
                    <option value="">All Purchases</option>
                    <option value="yes" {{ request('verified') === 'yes' ? 'selected' : '' }}>Verified</option>
                    <option value="no" {{ request('verified') === 'no' ? 'selected' : '' }}>Not Verified</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Reviews Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">Rating</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Review</th>
                        <th class="text-center">Status</th>
                        <th>Date</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $review->rating >= 4 ? 'success' : ($review->rating >= 3 ? 'warning' : 'danger') }} fs-6">
                                    {{ $review->rating }} <i class="bi bi-star-fill"></i>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $review->product->name ?? 'Deleted' }}</div>
                            @if($review->is_verified_purchase)
                            <span class="badge bg-success-subtle text-success">Verified Purchase</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $review->user->name ?? 'Guest' }}</div>
                            <small class="text-muted">{{ $review->user->email ?? '' }}</small>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 250px;">{{ $review->review }}</div>
                            @if($review->admin_response)
                            <small class="text-info"><i class="bi bi-reply"></i> Responded</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($review->is_approved)
                            <span class="badge bg-success">Approved</span>
                            @else
                            <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            {{ $review->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(!$review->is_approved)
                                <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_approved" value="1">
                                    <button type="submit" class="btn btn-outline-success" title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                    onsubmit="return confirm('Delete this review?');">
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
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-star fs-1 d-block mb-2"></i>
                                No reviews found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($reviews->hasPages())
    <div class="card-footer bg-white">
        {{ $reviews->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection