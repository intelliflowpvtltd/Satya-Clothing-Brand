@extends('admin.layouts.master')

@section('title', 'Order ' . $order->order_number)

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1">Order {{ $order->order_number }}</h1>
        <small class="text-muted">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</small>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print
        </button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Order Items ({{ $order->items->count() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                                            class="rounded me-2"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $item->product_name }}</div>
                                            <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->variant_size || $item->variant_color)
                                    <span class="badge bg-light text-dark">{{ $item->variant_size }}</span>
                                    <span class="badge bg-light text-dark">{{ $item->variant_color }}</span>
                                    @else
                                    —
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">₹{{ number_format($item->unit_price, 0) }}</td>
                                <td class="text-end fw-semibold">₹{{ number_format($item->total_price, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end">Subtotal:</td>
                                <td class="text-end">₹{{ number_format($order->subtotal, 0) }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="4" class="text-end">
                                    Discount
                                    @if($order->coupon)
                                    <span class="badge bg-success">{{ $order->coupon->code }}</span>
                                    @endif
                                    :
                                </td>
                                <td class="text-end text-success">-₹{{ number_format($order->discount_amount, 0) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="text-end">Shipping:</td>
                                <td class="text-end">₹{{ number_format($order->shipping_amount, 0) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">GST ({{ $order->gst_rate ?? 18 }}%):</td>
                                <td class="text-end">₹{{ number_format($order->gst_amount, 0) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold fs-5">Total:</td>
                                <td class="text-end fw-bold fs-5">₹{{ number_format($order->total_amount, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Update Order -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Update Order</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Order Status</label>
                            <select name="order_status" class="form-select">
                                <option value="">-- No Change --</option>
                                @foreach(['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled', 'returned'] as $status)
                                <option value="{{ $status }}" {{ $order->order_status === $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">-- No Change --</option>
                                @foreach(['pending', 'paid', 'failed', 'refunded'] as $status)
                                <option value="{{ $status }}" {{ $order->payment_status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control"
                                value="{{ $order->tracking_number }}" placeholder="Enter tracking number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tracking URL</label>
                            <input type="url" name="tracking_url" class="form-control"
                                value="{{ $order->tracking_url }}" placeholder="https://...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="2"
                                placeholder="Add a note (will be timestamped)"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-check-lg me-1"></i> Update Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admin Notes History -->
        @if($order->admin_notes)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Admin Notes</h5>
            </div>
            <div class="card-body">
                <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $order->admin_notes }}</pre>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Status -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Order Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Order Status:</span>
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
                    <span class="badge {{ $statusBadge }} fs-6">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Payment Status:</span>
                    @php
                    $paymentBadge = [
                    'pending' => 'bg-warning',
                    'paid' => 'bg-success',
                    'failed' => 'bg-danger',
                    'refunded' => 'bg-secondary',
                    ][$order->payment_status] ?? 'bg-secondary';
                    @endphp
                    <span class="badge {{ $paymentBadge }} fs-6">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Payment Method:</span>
                    <span class="fw-semibold">{{ strtoupper($order->payment_method) }}</span>
                </div>

                @if($order->tracking_number)
                <hr>
                <div class="mb-2">
                    <small class="text-muted">Tracking Number:</small>
                    <div class="fw-semibold">{{ $order->tracking_number }}</div>
                </div>
                @if($order->tracking_url)
                <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Track Shipment
                </a>
                @endif
                @endif
            </div>
        </div>

        <!-- Customer -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Customer</h5>
            </div>
            <div class="card-body">
                @if($order->user)
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-gradient rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 50px; height: 50px;">
                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div class="ms-3">
                        <div class="fw-semibold">{{ $order->user->name }}</div>
                        <small class="text-muted">{{ $order->user->email }}</small>
                    </div>
                </div>
                @if($order->user->mobile)
                <div class="mb-2">
                    <i class="bi bi-telephone me-2 text-muted"></i>{{ $order->user->mobile }}
                </div>
                @endif
                <a href="{{ route('admin.customers.show', $order->user) }}" class="btn btn-sm btn-outline-primary w-100">
                    View Customer
                </a>
                @else
                <p class="text-muted mb-0">Guest Customer</p>
                @endif
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Shipping Address</h5>
            </div>
            <div class="card-body">
                <div>{{ $order->shipping_name }}</div>
                <div>{{ $order->shipping_address }}</div>
                @if($order->shipping_landmark)
                <div class="text-muted">{{ $order->shipping_landmark }}</div>
                @endif
                <div>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}</div>
                @if($order->shipping_phone)
                <div class="mt-2">
                    <i class="bi bi-telephone me-1"></i> {{ $order->shipping_phone }}
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
                <div class="timeline">
                    <div class="mb-3">
                        <small class="text-muted">Ordered:</small>
                        <div>{{ $order->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    @if($order->paid_at)
                    <div class="mb-3">
                        <small class="text-muted">Paid:</small>
                        <div>{{ $order->paid_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                    @if($order->shipped_at)
                    <div class="mb-3">
                        <small class="text-muted">Shipped:</small>
                        <div>{{ $order->shipped_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                    @if($order->delivered_at)
                    <div class="mb-3">
                        <small class="text-muted">Delivered:</small>
                        <div>{{ $order->delivered_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection