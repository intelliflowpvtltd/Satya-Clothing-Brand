@extends('admin.layouts.master')

@section('title', 'Coupon ' . $coupon->code)

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
    <li class="breadcrumb-item active">{{ $coupon->code }}</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">{{ $coupon->code }}</h1>
    <div>
        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-primary-custom">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Coupon Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center py-5">
                <div class="bg-light rounded-3 p-4 mx-auto" style="max-width: 400px;">
                    <div class="font-monospace fs-2 fw-bold text-primary mb-2">{{ $coupon->code }}</div>
                    <div class="fs-1 fw-bold">
                        @if($coupon->discount_type === 'percentage')
                        {{ $coupon->discount_value }}% OFF
                        @else
                        ₹{{ number_format($coupon->discount_value, 0) }} OFF
                        @endif
                    </div>
                    @if($coupon->minimum_order_amount)
                    <div class="text-muted mt-2">Min. order ₹{{ number_format($coupon->minimum_order_amount, 0) }}</div>
                    @endif
                    @if($coupon->maximum_discount_amount)
                    <div class="text-muted">Max. discount ₹{{ number_format($coupon->maximum_discount_amount, 0) }}</div>
                    @endif

                    <div class="mt-4">
                        @if(!$coupon->is_active)
                        <span class="badge bg-danger fs-6">Inactive</span>
                        @elseif($coupon->valid_until->isPast())
                        <span class="badge bg-secondary fs-6">Expired</span>
                        @elseif($coupon->valid_from->isFuture())
                        <span class="badge bg-info fs-6">Starts {{ $coupon->valid_from->format('M d') }}</span>
                        @else
                        <span class="badge bg-success fs-6">Active</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Description:</th>
                        <td>{{ $coupon->description ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Discount Type:</th>
                        <td>{{ ucfirst($coupon->discount_type) }}</td>
                    </tr>
                    <tr>
                        <th>Valid From:</th>
                        <td>{{ $coupon->valid_from->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Valid Until:</th>
                        <td>{{ $coupon->valid_until->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Usage Limit:</th>
                        <td>{{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                    </tr>
                    <tr>
                        <th>Per User Limit:</th>
                        <td>{{ $coupon->usage_limit_per_user ?? 'Unlimited' }}</td>
                    </tr>
                    <tr>
                        <th>Times Used:</th>
                        <td>{{ $coupon->usage_count }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Usage History -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Usage History</h5>
            </div>
            <div class="card-body p-0">
                @if($coupon->usages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th class="text-end">Discount Applied</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupon->usages as $usage)
                            <tr>
                                <td>
                                    @if($usage->order)
                                    <a href="{{ route('admin.orders.show', $usage->order) }}">
                                        {{ $usage->order->order_number }}
                                    </a>
                                    @else
                                    —
                                    @endif
                                </td>
                                <td>{{ $usage->user->name ?? 'Guest' }}</td>
                                <td class="text-end text-success">₹{{ number_format($usage->discount_amount, 0) }}</td>
                                <td>{{ $usage->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-ticket-perforated fs-1 d-block mb-2"></i>
                    No usage history yet
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Stats -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Uses:</span>
                    <span class="fw-bold fs-5">{{ $coupon->usage_count }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Discount Given:</span>
                    <span class="fw-bold text-success">₹{{ number_format($coupon->usages->sum('discount_amount'), 0) }}</span>
                </div>
                @if($coupon->usage_limit)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Usage Progress</span>
                        <span>{{ $coupon->usage_count }}/{{ $coupon->usage_limit }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        @php $usagePercent = ($coupon->usage_count / $coupon->usage_limit) * 100; @endphp
                        <div class="progress-bar bg-{{ $usagePercent >= 90 ? 'danger' : ($usagePercent >= 50 ? 'warning' : 'success') }}"
                            role="progressbar"
                            style="width: {{ $usagePercent }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Timeline -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Timeline</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Created:</small>
                    <div>{{ $coupon->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div>
                    <small class="text-muted">Last Updated:</small>
                    <div>{{ $coupon->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection