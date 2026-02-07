@extends('admin.layouts.master')

@section('title', 'Orders')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Orders</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Orders</h1>
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
                <div class="fs-3 fw-bold text-info">{{ $stats['confirmed'] }}</div>
                <small class="text-muted">Confirmed</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['shipped'] }}</div>
                <small class="text-muted">Shipped</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-success">{{ $stats['delivered'] }}</div>
                <small class="text-muted">Delivered</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Order # or Customer..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment" class="form-select">
                    <option value="">All Payment</option>
                    <option value="pending" {{ request('payment') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}" placeholder="From Date">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Payment</th>
                        <th class="text-center">Status</th>
                        <th>Date</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-semibold">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</div>
                            <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $order->items->count() }}</span>
                        </td>
                        <td class="text-end">
                            <div class="fw-semibold">₹{{ number_format($order->total_amount, 0) }}</div>
                            @if($order->discount_amount > 0)
                            <small class="text-success">-₹{{ number_format($order->discount_amount, 0) }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                            $paymentBadge = [
                            'pending' => 'bg-warning',
                            'paid' => 'bg-success',
                            'failed' => 'bg-danger',
                            'refunded' => 'bg-secondary',
                            ][$order->payment_status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $paymentBadge }}">{{ ucfirst($order->payment_status) }}</span>
                        </td>
                        <td class="text-center">
                            @php
                            $statusBadge = [
                            'pending' => 'bg-warning',
                            'confirmed' => 'bg-info',
                            'processing' => 'bg-primary',
                            'shipped' => 'bg-primary',
                            'out_for_delivery' => 'bg-info',
                            'delivered' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            'returned' => 'bg-secondary',
                            ][$order->order_status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $statusBadge }}">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span>
                        </td>
                        <td>
                            <div>{{ $order->created_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <h6 class="dropdown-header">Update Status</h6>
                                        </li>
                                        @foreach(['confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                        @if($order->order_status !== $status)
                                        <li>
                                            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="order_status" value="{{ $status }}">
                                                <button type="submit" class="dropdown-item">
                                                    {{ ucfirst($status) }}
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No orders found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="card-footer bg-white">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection