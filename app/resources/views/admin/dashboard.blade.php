@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item active">Dashboard</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title">Dashboard</h1>
    <span class="text-muted">{{ now()->format('l, F j, Y') }}</span>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon bg-primary-gradient text-white">
                    <i class="bi bi-receipt"></i>
                </div>
                <span class="badge bg-success-subtle text-success">
                    <i class="bi bi-arrow-up"></i> Today
                </span>
            </div>
            <div class="stat-value">{{ number_format($todayOrders) }}</div>
            <div class="stat-label">Today's Orders</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon bg-accent-gradient text-white">
                    <i class="bi bi-currency-rupee"></i>
                </div>
                <span class="badge bg-success-subtle text-success">
                    <i class="bi bi-arrow-up"></i> Today
                </span>
            </div>
            <div class="stat-value">₹{{ number_format($todayRevenue, 0) }}</div>
            <div class="stat-label">Today's Revenue</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon bg-warning text-white">
                    <i class="bi bi-clock-history"></i>
                </div>
                <span class="badge bg-warning-subtle text-warning">Pending</span>
            </div>
            <div class="stat-value">{{ number_format($pendingOrders) }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon bg-info text-white">
                    <i class="bi bi-people"></i>
                </div>
                <span class="badge bg-info-subtle text-info">This Month</span>
            </div>
            <div class="stat-value">{{ number_format($newCustomers) }}</div>
            <div class="stat-label">New Customers</div>
        </div>
    </div>
</div>

<!-- Second Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon bg-success text-white">
                    <i class="bi bi-cart-check"></i>
                </div>
                <span class="badge bg-info-subtle text-info">This Month</span>
            </div>
            <div class="stat-value">{{ number_format($monthOrders) }}</div>
            <div class="stat-label">Monthly Orders</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon bg-success text-white">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <span class="badge bg-info-subtle text-info">This Month</span>
            </div>
            <div class="stat-value">₹{{ number_format($monthRevenue, 0) }}</div>
            <div class="stat-label">Monthly Revenue</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon bg-danger text-white">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <span class="badge bg-danger-subtle text-danger">Low Stock</span>
            </div>
            <div class="stat-value">{{ number_format($lowStockProducts) }}</div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="stat-icon bg-secondary text-white">
                    <i class="bi bi-box-seam"></i>
                </div>
                <span class="badge bg-danger-subtle text-danger">Out of Stock</span>
            </div>
            <div class="stat-value">{{ number_format($outOfStockProducts) }}</div>
            <div class="stat-label">Out of Stock Items</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Recent Orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-semibold">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="fw-semibold">₹{{ number_format($order->total_amount, 0) }}</td>
                                <td><span class="badge {{ $order->status_badge_class }}">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span></td>
                                <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No orders yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Order Status Overview</h5>
            </div>
            <div class="card-body">
                @php
                $statuses = [
                'pending' => ['label' => 'Pending', 'color' => 'warning'],
                'confirmed' => ['label' => 'Confirmed', 'color' => 'info'],
                'shipped' => ['label' => 'Shipped', 'color' => 'primary'],
                'delivered' => ['label' => 'Delivered', 'color' => 'success'],
                'cancelled' => ['label' => 'Cancelled', 'color' => 'danger'],
                ];
                $totalOrders = $orderStatusStats->sum();
                @endphp

                @foreach($statuses as $key => $status)
                @php
                $count = $orderStatusStats[$key] ?? 0;
                $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">{{ $status['label'] }}</span>
                        <span class="fw-semibold">{{ $count }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-{{ $status['color'] }}"
                            role="progressbar"
                            style="width: {{ $percentage }}%"
                            aria-valuenow="{{ $percentage }}"
                            aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Total Customers</span>
                    <span class="fs-4 fw-bold text-primary-custom">{{ number_format($totalCustomers) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection