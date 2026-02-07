@extends('admin.layouts.master')

@section('title', $customer->name)

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">{{ $customer->name }}</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">{{ $customer->name }}</h1>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-1"></i> Back to Customers
    </a>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Profile -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                @if($customer->avatar)
                <img src="{{ asset('storage/' . $customer->avatar) }}"
                    class="rounded-circle mb-3"
                    style="width: 100px; height: 100px; object-fit: cover;">
                @else
                <div class="bg-primary-gradient rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                    style="width: 100px; height: 100px; font-size: 2.5rem;">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                @endif
                <h4 class="mb-1">{{ $customer->name }}</h4>
                <p class="text-muted mb-3">{{ $customer->email }}</p>

                @if($customer->is_active)
                <span class="badge bg-success">Active</span>
                @else
                <span class="badge bg-danger">Inactive</span>
                @endif

                @if($customer->newsletter_subscribed)
                <span class="badge bg-info">Newsletter Subscribed</span>
                @endif
            </div>
            <div class="card-body border-top">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fs-4 fw-bold">{{ $stats['total_orders'] }}</div>
                        <small class="text-muted">Orders</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold">₹{{ number_format($stats['total_spent'], 0) }}</div>
                        <small class="text-muted">Spent</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold">₹{{ number_format($stats['avg_order'], 0) }}</div>
                        <small class="text-muted">Avg Order</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Email:</small>
                    <div>{{ $customer->email }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Mobile:</small>
                    <div>{{ $customer->mobile ?? '—' }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Gender:</small>
                    <div>{{ ucfirst($customer->gender) ?? '—' }}</div>
                </div>
                <div>
                    <small class="text-muted">Date of Birth:</small>
                    <div>{{ $customer->date_of_birth?->format('M d, Y') ?? '—' }}</div>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Account Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ $customer->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Account Active</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="newsletter_subscribed" name="newsletter_subscribed" value="1"
                            {{ $customer->newsletter_subscribed ? 'checked' : '' }}>
                        <label class="form-check-label" for="newsletter_subscribed">Newsletter Subscribed</label>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                </form>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Activity</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Joined:</small>
                    <div>{{ $customer->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Last Login:</small>
                    <div>{{ $customer->last_login_at?->format('M d, Y H:i') ?? 'Never' }}</div>
                </div>
                @if($customer->last_login_ip)
                <div>
                    <small class="text-muted">Last IP:</small>
                    <div>{{ $customer->last_login_ip }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Addresses -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Addresses ({{ $customer->addresses->count() }})</h5>
            </div>
            <div class="card-body">
                @if($customer->addresses->count() > 0)
                <div class="row g-3">
                    @foreach($customer->addresses as $address)
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-{{ $address->type === 'home' ? 'primary' : 'secondary' }}">{{ ucfirst($address->type) }}</span>
                                @if($address->is_default)
                                <span class="badge bg-success">Default</span>
                                @endif
                            </div>
                            <div class="fw-semibold">{{ $address->name }}</div>
                            <div>{{ $address->address_line_1 }}</div>
                            @if($address->address_line_2)
                            <div>{{ $address->address_line_2 }}</div>
                            @endif
                            <div>{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</div>
                            <div class="text-muted mt-2"><i class="bi bi-telephone me-1"></i>{{ $address->phone }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No addresses on file</p>
                @endif
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Recent Orders</h5>
                <span class="badge bg-secondary">{{ $customer->orders->count() }} total</span>
            </div>
            <div class="card-body p-0">
                @if($customer->orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders->take(10) as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="text-end">₹{{ number_format($order->total_amount, 0) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted">No orders yet</div>
                @endif
            </div>
        </div>

        <!-- Reviews -->
        @if($customer->reviews->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Reviews ({{ $customer->reviews->count() }})</h5>
            </div>
            <div class="card-body">
                @foreach($customer->reviews->take(5) as $review)
                <div class="d-flex mb-3 pb-3 border-bottom">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review->product->name ?? 'Product Deleted' }}</strong>
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
</div>
@endsection