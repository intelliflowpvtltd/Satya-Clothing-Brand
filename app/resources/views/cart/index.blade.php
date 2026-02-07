@extends('frontend.layouts.master')

@section('title', 'Shopping Cart')

@section('styles')
<style>
    .cart-section {
        padding: 100px 0 60px;
        min-height: 80vh;
        background: var(--bg-light);
    }

    .cart-header {
        margin-bottom: 2rem;
    }

    .cart-header h1 {
        font-size: 2rem;
        font-weight: 700;
    }

    .cart-table {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .cart-table table {
        margin-bottom: 0;
    }

    .cart-table th {
        background: var(--bg-light);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: var(--text-muted);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--border-color);
    }

    .cart-table td {
        padding: 1.5rem;
        vertical-align: middle;
    }

    .cart-product {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .cart-product img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
    }

    .cart-product-info h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .cart-product-variant {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quantity-control button {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid var(--border-color);
        background: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .quantity-control button:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .quantity-control input {
        width: 50px;
        text-align: center;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        padding: 5px;
    }

    .cart-price {
        font-weight: 600;
        color: var(--primary);
    }

    .cart-remove {
        color: var(--text-muted);
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .cart-remove:hover {
        color: #dc3545;
    }

    .cart-summary {
        background: #fff;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 100px;
    }

    .cart-summary h4 {
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .summary-row.total {
        font-size: 1.25rem;
        font-weight: 700;
        padding-top: 1rem;
        border-top: 2px solid var(--border-color);
        margin-top: 1rem;
    }

    .summary-row .discount {
        color: #198754;
    }

    .coupon-form {
        margin-bottom: 1.5rem;
    }

    .coupon-form .input-group {
        background: var(--bg-light);
        border-radius: 10px;
        overflow: hidden;
    }

    .coupon-form input {
        border: none;
        background: transparent;
        padding: 12px 15px;
    }

    .coupon-form button {
        border: none;
        background: var(--primary);
        color: #fff;
        padding: 0 20px;
        font-weight: 600;
    }

    .applied-coupon {
        background: rgba(25, 135, 84, 0.1);
        border: 1px solid #198754;
        border-radius: 10px;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .applied-coupon .code {
        font-weight: 600;
        color: #198754;
    }

    .btn-checkout {
        width: 100%;
        padding: 15px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 15px;
    }

    .empty-cart i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    .empty-cart h4 {
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<section class="cart-section">
    <div class="container">
        <div class="cart-header">
            <h1><i class="bi bi-cart3 me-2"></i>Shopping Cart</h1>
        </div>

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

        @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <div class="cart-product">
                                        <img src="{{ $item->product->images->first() 
                                                    ? asset('storage/' . $item->product->images->first()->image_path) 
                                                    : asset('images/placeholder.jpg') }}"
                                            alt="{{ $item->product->name }}">
                                        <div class="cart-product-info">
                                            <h6>
                                                <a href="{{ route('shop.product', $item->product->slug) }}">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h6>
                                            <div class="cart-product-variant">
                                                Size: {{ $item->variant->size }} |
                                                Color: {{ $item->variant->color }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="cart-price">
                                    @if($item->product->is_on_sale)
                                    <del class="text-muted">₹{{ number_format($item->product->price, 0) }}</del>
                                    <span class="ms-1">₹{{ number_format($item->product->sale_price, 0) }}</span>
                                    @else
                                    ₹{{ number_format($item->product->price, 0) }}
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="quantity-control">
                                            <button type="button" onclick="decreaseQty(this)">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                min="1" max="{{ $item->variant->stock_quantity }}"
                                                onchange="this.form.submit()">
                                            <button type="button" onclick="increaseQty(this)">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="cart-price">
                                    ₹{{ number_format($item->quantity * ($item->product->is_on_sale 
                                                ? $item->product->sale_price 
                                                : $item->product->price), 0) }}
                                </td>
                                <td>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link cart-remove p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4>Order Summary</h4>

                    @if($coupon)
                    <div class="applied-coupon">
                        <div>
                            <i class="bi bi-ticket-perforated text-success me-2"></i>
                            <span class="code">{{ $coupon['code'] }}</span>
                        </div>
                        <form action="{{ route('cart.coupon.remove') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                    </div>
                    @else
                    <form action="{{ route('cart.coupon') }}" method="POST" class="coupon-form">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="code" class="form-control"
                                placeholder="Enter coupon code" required>
                            <button type="submit">Apply</button>
                        </div>
                    </form>
                    @endif

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($subtotal, 0) }}</span>
                    </div>

                    @if($discount > 0)
                    <div class="summary-row">
                        <span>Discount</span>
                        <span class="discount">-₹{{ number_format($discount, 0) }}</span>
                    </div>
                    @endif

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="text-success">Free</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span>₹{{ number_format($total, 0) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary-custom btn-checkout">
                        Proceed to Checkout <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <a href="{{ route('shop') }}" class="btn btn-outline-dark w-100 mt-3">
                        <i class="bi bi-arrow-left me-2"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="empty-cart">
            <i class="bi bi-cart-x"></i>
            <h4>Your cart is empty</h4>
            <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary-custom px-4">
                Start Shopping <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    function increaseQty(btn) {
        const input = btn.parentNode.querySelector('input');
        const max = parseInt(input.getAttribute('max'));
        let val = parseInt(input.value);
        if (val < max) {
            input.value = val + 1;
            input.form.submit();
        }
    }

    function decreaseQty(btn) {
        const input = btn.parentNode.querySelector('input');
        let val = parseInt(input.value);
        if (val > 1) {
            input.value = val - 1;
            input.form.submit();
        }
    }
</script>
@endsection