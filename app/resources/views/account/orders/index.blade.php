@extends('frontend.layouts.master')

@section('title', 'My Orders')

@section('styles')
<style>
    .orders-section {
        padding: 100px 0 60px;
        min-height: 80vh;
        background: var(--bg-light);
    }

    .section-header {
        margin-bottom: 2rem;
    }

    .section-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .order-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
    }

    .order-number {
        font-weight: 700;
        color: var(--primary);
    }

    .order-date {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .order-body {
        padding: 1.5rem;
    }

    .order-items {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .order-item-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .order-item-count {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: var(--bg-light);
        border-radius: 8px;
        font-weight: 600;
        color: var(--text-muted);
    }

    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    .order-total {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 15px;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<section class="orders-section">
    <div class="container">
        <div class="section-header">
            <h1><i class="bi bi-bag-check me-2"></i>My Orders</h1>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($orders->count() > 0)
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-number">{{ $order->order_number }}</span>
                    <span class="order-date ms-3">
                        <i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>
                <div>
                    <span class="badge {{ $order->status_badge_class }}">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span>
                    <span class="badge {{ $order->payment_badge_class }} ms-1">{{ ucfirst($order->payment_status) }}</span>
                </div>
            </div>
            <div class="order-body">
                <div class="order-items">
                    @foreach($order->items->take(3) as $item)
                    <img src="{{ $item->product && $item->product->images->first() 
                                    ? asset('storage/' . $item->product->images->first()->image_path) 
                                    : asset('images/placeholder.jpg') }}"
                        alt="{{ $item->product_name }}" class="order-item-img">
                    @endforeach
                    @if($order->items->count() > 3)
                    <div class="order-item-count">
                        +{{ $order->items->count() - 3 }}
                    </div>
                    @endif
                </div>
                <div class="text-muted">
                    {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                </div>
            </div>
            <div class="order-footer">
                <div class="order-total">
                    Total: ₹{{ number_format($order->total_amount, 0) }}
                </div>
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline-primary">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-bag-x"></i>
            <h4>No Orders Yet</h4>
            <p class="text-muted mb-4">You haven't placed any orders yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary-custom px-4">
                Start Shopping <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection