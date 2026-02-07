@extends('frontend.layouts.master')

@section('title', 'Order Confirmed')

@section('styles')
<style>
    .success-section {
        padding: 120px 0 80px;
        min-height: 80vh;
        background: linear-gradient(135deg, var(--bg-light) 0%, #fff 100%);
    }

    .success-card {
        background: #fff;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        max-width: 700px;
        margin: 0 auto;
        text-align: center;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .success-icon i {
        font-size: 3rem;
        color: #fff;
    }

    .success-card h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #198754;
    }

    .success-card .subtitle {
        color: var(--text-muted);
        margin-bottom: 2rem;
    }

    .order-details {
        background: var(--bg-light);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: left;
    }

    .order-details .order-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
        text-align: center;
    }

    .order-details .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px dashed var(--border-color);
    }

    .order-details .detail-row:last-child {
        border-bottom: none;
    }

    .order-details .detail-row span:first-child {
        color: var(--text-muted);
    }

    .order-details .detail-row strong {
        color: var(--text-dark);
    }

    .order-items {
        text-align: left;
        margin-bottom: 2rem;
    }

    .order-items h6 {
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .order-items .item {
        display: flex;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .order-items .item:last-child {
        border-bottom: none;
    }

    .order-items .item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .order-items .item-name {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .order-items .item-variant {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .order-items .item-price {
        margin-left: auto;
        font-weight: 600;
        color: var(--primary);
    }

    .address-info {
        background: var(--bg-light);
        border-radius: 10px;
        padding: 1rem;
        text-align: left;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .address-info strong {
        color: var(--primary);
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<section class="success-section">
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <h2>Order Placed Successfully!</h2>
            <p class="subtitle">Thank you for your purchase. We'll send you a confirmation email shortly.</p>

            <div class="order-details">
                <div class="order-number">
                    Order #{{ $order->order_number }}
                </div>
                <div class="detail-row">
                    <span>Order Date</span>
                    <strong>{{ $order->created_at->format('d M Y, h:i A') }}</strong>
                </div>
                <div class="detail-row">
                    <span>Payment Method</span>
                    <strong>{{ strtoupper($order->payment_method) }}</strong>
                </div>
                <div class="detail-row">
                    <span>Payment Status</span>
                    <strong>
                        <span class="badge {{ $order->payment_badge_class }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </strong>
                </div>
                <div class="detail-row">
                    <span>Total Amount</span>
                    <strong class="text-primary">₹{{ number_format($order->total_amount, 0) }}</strong>
                </div>
            </div>

            <div class="order-items">
                <h6><i class="bi bi-bag me-2"></i>Order Items</h6>
                @foreach($order->items as $item)
                <div class="item">
                    <img src="{{ $item->product && $item->product->images->first() 
                            ? asset('storage/' . $item->product->images->first()->image_path) 
                            : asset('images/placeholder.jpg') }}"
                        alt="{{ $item->product_name }}">
                    <div>
                        <div class="item-name">{{ $item->product_name }}</div>
                        <div class="item-variant">{{ $item->variant_details }} × {{ $item->quantity }}</div>
                    </div>
                    <div class="item-price">₹{{ number_format($item->total, 0) }}</div>
                </div>
                @endforeach
            </div>

            <div class="address-info">
                <strong>Delivery Address:</strong><br>
                {{ $order->address->full_name }}<br>
                {{ $order->address->full_address }}<br>
                <i class="bi bi-telephone me-1"></i>{{ $order->address->mobile }}
            </div>

            <div class="action-buttons">
                <a href="{{ route('shop') }}" class="btn btn-primary-custom">
                    Continue Shopping
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-dark">
                    Go to Home
                </a>
            </div>
        </div>
    </div>
</section>
@endsection