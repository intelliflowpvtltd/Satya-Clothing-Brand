@extends('admin.layouts.master')

@section('title', 'Customers')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Customers</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Customers</h1>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-primary">{{ number_format($stats['total']) }}</div>
                <small class="text-muted">Total Customers</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-success">{{ number_format($stats['active']) }}</div>
                <small class="text-muted">Active</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-info">{{ number_format($stats['new_this_month']) }}</div>
                <small class="text-muted">New This Month</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <div class="fs-3 fw-bold text-warning">{{ number_format($stats['newsletter']) }}</div>
                <small class="text-muted">Newsletter</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Search by name, email, phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="newsletter" class="form-select">
                    <option value="">All Newsletter</option>
                    <option value="yes" {{ request('newsletter') === 'yes' ? 'selected' : '' }}>Subscribed</option>
                    <option value="no" {{ request('newsletter') === 'no' ? 'selected' : '' }}>Not Subscribed</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th class="text-center">Orders</th>
                        <th class="text-center">Reviews</th>
                        <th class="text-center">Status</th>
                        <th>Joined</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($customer->avatar)
                                <img src="{{ asset('storage/' . $customer->avatar) }}"
                                    class="rounded-circle me-2"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                <div class="bg-primary-gradient rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                    style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $customer->name }}</div>
                                    @if($customer->newsletter_subscribed)
                                    <span class="badge bg-info-subtle text-info">Newsletter</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $customer->email }}</div>
                            <small class="text-muted">{{ $customer->mobile ?? '—' }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $customer->orders_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $customer->reviews_count }}</span>
                        </td>
                        <td class="text-center">
                            @if($customer->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $customer->created_at->format('M d, Y') }}</div>
                            @if($customer->last_login_at)
                            <small class="text-muted">Last: {{ $customer->last_login_at->diffForHumans() }}</small>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                No customers found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($customers->hasPages())
    <div class="card-footer bg-white">
        {{ $customers->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection