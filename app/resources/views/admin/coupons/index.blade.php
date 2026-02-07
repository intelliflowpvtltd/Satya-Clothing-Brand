@extends('admin.layouts.master')

@section('title', 'Coupons')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Coupons</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Coupons</h1>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i> Create Coupon
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.coupons.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Search by code..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Coupons Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Validity</th>
                        <th class="text-center">Usage</th>
                        <th class="text-center">Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr>
                        <td>
                            <div class="fw-semibold font-monospace">{{ $coupon->code }}</div>
                            @if($coupon->description)
                            <small class="text-muted">{{ Str::limit($coupon->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $coupon->discount_type === 'percentage' ? 'primary' : 'info' }} fs-6">
                                @if($coupon->discount_type === 'percentage')
                                {{ $coupon->discount_value }}% OFF
                                @else
                                ₹{{ number_format($coupon->discount_value, 0) }} OFF
                                @endif
                            </span>
                            @if($coupon->minimum_order_amount)
                            <div class="small text-muted mt-1">Min: ₹{{ number_format($coupon->minimum_order_amount, 0) }}</div>
                            @endif
                        </td>
                        <td>
                            <div>{{ $coupon->valid_from->format('M d') }} - {{ $coupon->valid_until->format('M d, Y') }}</div>
                            @if($coupon->valid_until->isPast())
                            <span class="badge bg-danger-subtle text-danger">Expired</span>
                            @elseif($coupon->valid_from->isFuture())
                            <span class="badge bg-info-subtle text-info">Scheduled</span>
                            @else
                            <small class="text-muted">{{ $coupon->valid_until->diffForHumans() }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $coupon->usages_count }}</span>
                            @if($coupon->usage_limit)
                            <span class="text-muted">/ {{ $coupon->usage_limit }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!$coupon->is_active)
                            <span class="badge bg-danger">Inactive</span>
                            @elseif($coupon->valid_until->isPast())
                            <span class="badge bg-secondary">Expired</span>
                            @elseif($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit)
                            <span class="badge bg-warning">Limit Reached</span>
                            @else
                            <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($coupon->usage_count == 0)
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-ticket-perforated fs-1 d-block mb-2"></i>
                                No coupons found
                            </div>
                            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary-custom mt-3">
                                <i class="bi bi-plus-lg me-1"></i> Create Your First Coupon
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($coupons->hasPages())
    <div class="card-footer bg-white">
        {{ $coupons->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection