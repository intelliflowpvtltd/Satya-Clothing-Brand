@extends('admin.layouts.master')

@section('title', 'Edit Coupon')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
    <li class="breadcrumb-item active">Edit {{ $coupon->code }}</li>
</ol>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Edit Coupon</h1>
</div>

<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Coupon Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase @error('code') is-invalid @enderror"
                                id="code" name="code" value="{{ old('code', $coupon->code) }}" required maxlength="50">
                            @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" value="{{ old('description', $coupon->description) }}" maxlength="255">
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required>
                                <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                            </select>
                            @error('discount_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror"
                                id="discount_value" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" required min="0">
                            @error('discount_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="minimum_order_amount" class="form-label">Minimum Order Amount (₹)</label>
                            <input type="number" step="0.01" class="form-control @error('minimum_order_amount') is-invalid @enderror"
                                id="minimum_order_amount" name="minimum_order_amount" value="{{ old('minimum_order_amount', $coupon->minimum_order_amount) }}" min="0">
                            @error('minimum_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="maximum_discount_amount" class="form-label">Maximum Discount Amount (₹)</label>
                            <input type="number" step="0.01" class="form-control @error('maximum_discount_amount') is-invalid @enderror"
                                id="maximum_discount_amount" name="maximum_discount_amount" value="{{ old('maximum_discount_amount', $coupon->maximum_discount_amount) }}" min="0">
                            @error('maximum_discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validity -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Validity Period</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="valid_from" class="form-label">Valid From <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('valid_from') is-invalid @enderror"
                                id="valid_from" name="valid_from" value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d\TH:i')) }}" required>
                            @error('valid_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="valid_until" class="form-label">Valid Until <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror"
                                id="valid_until" name="valid_until" value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d\TH:i')) }}" required>
                            @error('valid_until')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Usage Limits</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="usage_limit" class="form-label">Total Usage Limit</label>
                            <input type="number" class="form-control @error('usage_limit') is-invalid @enderror"
                                id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
                            @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="usage_limit_per_user" class="form-label">Per User Limit</label>
                            <input type="number" class="form-control @error('usage_limit_per_user') is-invalid @enderror"
                                id="usage_limit_per_user" name="usage_limit_per_user" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}" min="1">
                            @error('usage_limit_per_user')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Usage Info</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Times Used:</span>
                        <span class="fw-semibold">{{ $coupon->usage_count }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Created:</span>
                        <span>{{ $coupon->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Updated:</span>
                        <span>{{ $coupon->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Status</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                        <i class="bi bi-check-lg me-1"></i> Update Coupon
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection