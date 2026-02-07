@extends('frontend.layouts.master')

@section('title', 'Order #' . $order->order_number)

@section('styles')
<style>
    .order-detail-section {
        padding: 100px 0 60px;
        min-height: 80vh;
        background: var(--bg-light);
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    .back-link:hover {
        color: var(--accent);
    }

    .order-header-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .order-header-card h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .order-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        color: var(--text-muted);
    }

    .order-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .order-card h5 {
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
    }

    /* Timeline */
    .order-timeline {
        position: relative;
        padding-left: 30px;
    }

    .order-timeline::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: var(--border-color);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--border-color);
        border: 2px solid #fff;
    }

    .timeline-item.completed::before {
        background: #198754;
    }

    .timeline-item.current::before {
        background: var(--accent);
        box-shadow: 0 0 0 4px rgba(233, 69, 96, 0.2);
    }

    .timeline-item.cancelled::before {
        background: #dc3545;
    }

    .timeline-title {
        font-weight: 600;
        margin-bottom: 3px;
    }

    .timeline-date {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* Order Items */
    .order-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
    }

    .order-item-info h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .order-item-variant {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .order-item-price {
        text-align: right;
        font-weight: 600;
        color: var(--primary);
    }

    /* Summary */
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .summary-row.total {
        font-size: 1.2rem;
        font-weight: 700;
        padding-top: 1rem;
        border-top: 2px solid var(--border-color);
        margin-top: 1rem;
    }

    .summary-row .discount {
        color: #198754;
    }

    /* Address */
    .address-info {
        line-height: 1.8;
    }

    .address-info strong {
        color: var(--primary);
    }

    .btn-cancel {
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-cancel:hover {
        background: #dc3545;
        color: #fff;
    }
</style>
@endsection

@section('content')
<section class="order-detail-section">
    <div class="container">
        <a href="{{ route('account.orders') }}" class="back-link">
            <i class="bi bi-arrow-left me-2"></i>Back to Orders
        </a>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Order Header -->
        <div class="order-header-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h2>Order #{{ $order->order_number }}</h2>
                    <div class="order-meta">
                        <span><i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y, h:i A') }}</span>
                        <span><i class="bi bi-credit-card me-1"></i>{{ strtoupper($order->payment_method) }}</span>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge {{ $order->status_badge_class }} fs-6">
                        {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                    </span>
                    <br>
                    <span class="badge {{ $order->payment_badge_class }} mt-2">
                        Payment: {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Timeline -->
                <div class="order-card">
                    <h5><i class="bi bi-clock-history me-2"></i>Order Timeline</h5>
                    <div class="order-timeline">
                        @php
                        $statuses = ['pending', 'confirmed', 'packed', 'shipped', 'out_for_delivery', 'delivered'];
                        $currentIndex = array_search($order->order_status, $statuses);
                        $isCancelled = $order->order_status === 'cancelled';
                        @endphp

                        @if($isCancelled)
                        <div class="timeline-item cancelled">
                            <div class="timeline-title">Order Cancelled</div>
                            <div class="timeline-date">{{ $order->updated_at->format('d M Y, h:i A') }}</div>
                        </div>
                        @else
                        @foreach($statuses as $index => $status)
                        @php
                        $isCompleted = $index < $currentIndex;
                            $isCurrent=$index===$currentIndex;
                            @endphp
                            <div class="timeline-item {{ $isCompleted ? 'completed' : ($isCurrent ? 'current' : '') }}">
                            <div class="timeline-title">{{ ucfirst(str_replace('_', ' ', $status)) }}</div>
                            @if($isCompleted || $isCurrent)
                            <div class="timeline-date">
                                @if($status === 'shipped' && $order->shipped_at)
                                {{ $order->shipped_at->format('d M Y, h:i A') }}
                                @elseif($status === 'delivered' && $order->delivered_at)
                                {{ $order->delivered_at->format('d M Y, h:i A') }}
                                @elseif($isCurrent)
                                {{ $order->updated_at->format('d M Y, h:i A') }}
                                @endif
                            </div>
                            @endif
                    </div>
                    @endforeach
                    @endif
                </div>

                @if($order->awb_number)
                <div class="mt-3 p-3 bg-light rounded">
                    <strong>Tracking:</strong> {{ $order->courier_name }} - {{ $order->awb_number }}
                </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="order-card">
                <h5><i class="bi bi-bag me-2"></i>Order Items</h5>
                @foreach($order->items as $item)
                <div class="order-item">
                    <img src="{{ $item->product && $item->product->images->first() 
                                ? asset('storage/' . $item->product->images->first()->image_path) 
                                : asset('images/placeholder.jpg') }}"
                        alt="{{ $item->product_name }}">
                    <div class="order-item-info flex-grow-1">
                        <h6>{{ $item->product_name }}</h6>
                        <div class="order-item-variant">{{ $item->variant_details }}</div>
                        <div class="order-item-variant">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 0) }}</div>
                    </div>
                    <div class="order-item-price">
                        ₹{{ number_format($item->total, 0) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="order-card">
                <h5>Order Summary</h5>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($order->subtotal, 0) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="summary-row">
                    <span>Discount</span>
                    <span class="discount">-₹{{ number_format($order->discount, 0) }}</span>
                </div>
                @endif
                @if($order->shipping_charges > 0)
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>₹{{ number_format($order->shipping_charges, 0) }}</span>
                </div>
                @endif
                @if($order->cod_charges > 0)
                <div class="summary-row">
                    <span>COD Charges</span>
                    <span>₹{{ number_format($order->cod_charges, 0) }}</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="order-card">
                <h5><i class="bi bi-geo-alt me-2"></i>Delivery Address</h5>
                <div class="address-info">
                    <strong>{{ $order->address->full_name }}</strong><br>
                    {{ $order->address->address_line1 }}<br>
                    @if($order->address->address_line2)
                    {{ $order->address->address_line2 }}<br>
                    @endif
                    {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}<br>
                    <i class="bi bi-telephone me-1"></i>{{ $order->address->mobile }}
                </div>
            </div>

            <!-- Actions -->
            @if($order->canBeCancelled())
            <div class="order-card">
                <h5>Actions</h5>
                <form action="{{ route('account.orders.cancel', $order) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle me-2"></i>Cancel Order
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    </div>
</section>
@endsection