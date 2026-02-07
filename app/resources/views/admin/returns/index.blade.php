@extends('admin.layouts.master')

@section('title', 'Returns')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Returns</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Returns</h1>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
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
                <div class="fs-3 fw-bold text-info">{{ $stats['approved'] }}</div>
                <small class="text-muted">Approved</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['picked_up'] }}</div>
                <small class="text-muted">In Transit</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-success">{{ $stats['completed'] }}</div>
                <small class="text-muted">Completed</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.returns.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Return # or Order #..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="pickup_scheduled" {{ request('status') === 'pickup_scheduled' ? 'selected' : '' }}>Pickup Scheduled</option>
                    <option value="picked_up" {{ request('status') === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
                    <option value="refund_processed" {{ request('status') === 'refund_processed' ? 'selected' : '' }}>Refunded</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="reason" class="form-select">
                    <option value="">All Reasons</option>
                    <option value="size_issue" {{ request('reason') === 'size_issue' ? 'selected' : '' }}>Size Issue</option>
                    <option value="defective" {{ request('reason') === 'defective' ? 'selected' : '' }}>Defective</option>
                    <option value="wrong_product" {{ request('reason') === 'wrong_product' ? 'selected' : '' }}>Wrong Product</option>
                    <option value="not_as_described" {{ request('reason') === 'not_as_described' ? 'selected' : '' }}>Not As Described</option>
                    <option value="other" {{ request('reason') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Returns Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Return #</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Reason</th>
                        <th class="text-center">Status</th>
                        <th>Requested</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    <tr>
                        <td>
                            <a href="{{ route('admin.returns.show', $return) }}" class="fw-semibold text-decoration-none">
                                {{ $return->return_number }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $return->order) }}">
                                {{ $return->order->order_number }}
                            </a>
                        </td>
                        <td>{{ $return->user->name ?? 'Guest' }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;">
                                {{ $return->orderItem->product_name ?? 'N/A' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $return->return_reason)) }}</span>
                        </td>
                        <td class="text-center">
                            @php
                            $statusColors = [
                            'pending' => 'warning',
                            'approved' => 'info',
                            'rejected' => 'danger',
                            'pickup_scheduled' => 'info',
                            'picked_up' => 'primary',
                            'in_transit' => 'primary',
                            'received' => 'info',
                            'qc_passed' => 'success',
                            'qc_failed' => 'danger',
                            'refund_processing' => 'warning',
                            'refund_processed' => 'success',
                            'completed' => 'success',
                            ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$return->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $return->status)) }}
                            </span>
                        </td>
                        <td>
                            {{ $return->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.returns.show', $return) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-arrow-return-left fs-1 d-block mb-2"></i>
                                No return requests found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($returns->hasPages())
    <div class="card-footer bg-white">
        {{ $returns->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection