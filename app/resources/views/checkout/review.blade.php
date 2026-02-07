@extends('frontend.layouts.master')

@section('title', 'Review Order')

@section('styles')
<style>
    .checkout-section {
        padding: 100px 0 60px;
        min-height: 80vh;
        background: var(--bg-light);
    }

    .checkout-header {
        margin-bottom: 2rem;
    }

    .checkout-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .checkout-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .checkout-step {
        display: flex;
        align-items: center;
        padding: 0 1.5rem;
    }

    .checkout-step .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--border-color);
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 10px;
    }

    .checkout-step.active .step-number {
        background: var(--accent);
        color: #fff;
    }

    .checkout-step.completed .step-number {
        background: #198754;
        color: #fff;
    }

    .checkout-step-line {
        width: 60px;
        height: 2px;
        background: var(--border-color);
    }

    .checkout-step.completed+.checkout-step-line {
        background: #198754;
    }

    .checkout-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .checkout-card h5 {
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
    }

    .address-info {
        line-height: 1.8;
    }

    .address-info strong {
        color: var(--primary);
    }

    .order-items .item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .order-items .item:last-child {
        border-bottom: none;
    }

    .order-items .item img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
    }

    .order-items .item-info h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .order-items .item-variant {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .order-items .item-price {
        text-align: right;
        font-weight: 600;
        color: var(--primary);
    }

    .payment-option {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .payment-option:hover {
        border-color: var(--accent);
    }

    .payment-option.selected {
        border-color: var(--accent);
        background: rgba(233, 69, 96, 0.05);
    }

    .payment-option input {
        margin-right: 12px;
    }

    .payment-option i {
        font-size: 1.25rem;
        margin-right: 10px;
        color: var(--primary);
    }

    .payment-option .badge {
        margin-left: auto;
    }

    .summary-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 100px;
    }

    .summary-card h5 {
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
    }

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

    .btn-place-order {
        width: 100%;
        padding: 15px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .order-notes textarea {
        width: 100%;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 12px;
        resize: none;
    }

    .order-notes textarea:focus {
        border-color: var(--accent);
        outline: none;
    }
</style>
@endsection

@section('content')
<section class="checkout-section">
    <div class="container">
        <div class="checkout-header text-center">
            <h1>Checkout</h1>
        </div>

        <!-- Checkout Steps -->
        <div class="checkout-steps">
            <div class="checkout-step completed">
                <span class="step-number"><i class="bi bi-check"></i></span>
                <span>Address</span>
            </div>
            <div class="checkout-step-line"></div>
            <div class="checkout-step active">
                <span class="step-number">2</span>
                <span>Review</span>
            </div>
            <div class="checkout-step-line"></div>
            <div class="checkout-step">
                <span class="step-number">3</span>
                <span>Payment</span>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <!-- Delivery Address -->
                    <div class="checkout-card">
                        <h5>
                            <i class="bi bi-geo-alt me-2"></i>Delivery Address
                            <a href="{{ route('checkout.address') }}" class="btn btn-sm btn-outline-primary float-end">
                                Change
                            </a>
                        </h5>
                        <div class="address-info">
                            <strong>{{ $address->full_name }}</strong><br>
                            {{ $address->address_line1 }}<br>
                            @if($address->address_line2)
                            {{ $address->address_line2 }}<br>
                            @endif
                            @if($address->landmark)
                            Landmark: {{ $address->landmark }}<br>
                            @endif
                            {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}<br>
                            <i class="bi bi-telephone me-1"></i>{{ $address->mobile }}
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="checkout-card">
                        <h5><i class="bi bi-bag me-2"></i>Order Items ({{ $cartItems->sum('quantity') }})</h5>
                        <div class="order-items">
                            @foreach($cartItems as $item)
                            <div class="item">
                                <img src="{{ $item->product->images->first() 
                                        ? asset('storage/' . $item->product->images->first()->image_path) 
                                        : asset('images/placeholder.jpg') }}"
                                    alt="{{ $item->product->name }}">
                                <div class="item-info flex-grow-1">
                                    <h6>{{ $item->product->name }}</h6>
                                    <div class="item-variant">
                                        Size: {{ $item->variant->size }} |
                                        Color: {{ $item->variant->color }}
                                    </div>
                                    <div class="item-variant">Qty: {{ $item->quantity }}</div>
                                </div>
                                <div class="item-price">
                                    ₹{{ number_format($item->quantity * ($item->product->is_on_sale 
                                            ? $item->product->sale_price 
                                            : $item->product->price), 0) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-card">
                        <h5><i class="bi bi-credit-card me-2"></i>Payment Method</h5>

                        <label class="payment-option selected" onclick="selectPayment(this, 'cod')">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <i class="bi bi-cash"></i>
                            <span>Cash on Delivery</span>
                            <span class="badge bg-warning text-dark">+₹50</span>
                        </label>

                        <label class="payment-option" onclick="selectPayment(this, 'upi')">
                            <input type="radio" name="payment_method" value="upi">
                            <i class="bi bi-phone"></i>
                            <span>UPI (GPay, PhonePe, Paytm)</span>
                        </label>

                        <label class="payment-option" onclick="selectPayment(this, 'card')">
                            <input type="radio" name="payment_method" value="card">
                            <i class="bi bi-credit-card-2-front"></i>
                            <span>Credit / Debit Card</span>
                        </label>

                        <label class="payment-option" onclick="selectPayment(this, 'netbanking')">
                            <input type="radio" name="payment_method" value="netbanking">
                            <i class="bi bi-bank"></i>
                            <span>Net Banking</span>
                        </label>
                    </div>

                    <!-- Order Notes -->
                    <div class="checkout-card">
                        <h5><i class="bi bi-chat-left-text me-2"></i>Order Notes (Optional)</h5>
                        <div class="order-notes">
                            <textarea name="notes" rows="3"
                                placeholder="Any special instructions for your order..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="summary-card">
                        <h5>Order Summary</h5>

                        <div class="summary-row">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span>₹{{ number_format($subtotal, 0) }}</span>
                        </div>

                        @if($discount > 0)
                        <div class="summary-row">
                            <span>
                                Discount
                                @if($coupon)
                                <span class="badge bg-success">{{ $coupon['code'] }}</span>
                                @endif
                            </span>
                            <span class="discount">-₹{{ number_format($discount, 0) }}</span>
                        </div>
                        @endif

                        <div class="summary-row">
                            <span>Shipping</span>
                            <span class="text-success">Free</span>
                        </div>

                        <div class="summary-row" id="codChargesRow">
                            <span>COD Charges</span>
                            <span id="codChargesValue">₹50</span>
                        </div>

                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="totalAmount">₹{{ number_format($total + 50, 0) }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary-custom btn-place-order">
                            <i class="bi bi-lock me-2"></i>Place Order
                        </button>

                        <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                            <i class="bi bi-shield-check me-1"></i>
                            Secure checkout powered by Razorpay
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const baseTotal = {
        {
            $total
        }
    };

    function selectPayment(element, method) {
        document.querySelectorAll('.payment-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        element.classList.add('selected');
        element.querySelector('input').checked = true;

        // Update COD charges
        const codRow = document.getElementById('codChargesRow');
        const codValue = document.getElementById('codChargesValue');
        const totalEl = document.getElementById('totalAmount');

        if (method === 'cod') {
            codRow.style.display = 'flex';
            codValue.textContent = '₹50';
            totalEl.textContent = '₹' + (baseTotal + 50).toLocaleString('en-IN');
        } else {
            codRow.style.display = 'none';
            totalEl.textContent = '₹' + baseTotal.toLocaleString('en-IN');
        }
    }
</script>
@endsection