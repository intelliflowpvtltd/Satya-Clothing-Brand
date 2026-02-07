@extends('frontend.layouts.master')

@section('title', 'Select Address')

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

    .address-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .address-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .address-card:hover {
        border-color: var(--accent);
    }

    .address-card.selected {
        border-color: var(--accent);
        background: rgba(233, 69, 96, 0.05);
    }

    .address-card.selected::after {
        content: '\f26e';
        font-family: 'bootstrap-icons';
        position: absolute;
        top: 15px;
        right: 15px;
        color: var(--accent);
        font-size: 1.5rem;
    }

    .address-type-badge {
        display: inline-block;
        padding: 4px 12px;
        background: var(--bg-light);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .address-card h5 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .address-card .mobile {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .address-card .address-text {
        color: var(--text-dark);
        line-height: 1.6;
    }

    .default-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary);
        color: #fff;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .add-address-card {
        background: #fff;
        border: 2px dashed var(--border-color);
        border-radius: 15px;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        min-height: 200px;
    }

    .add-address-card:hover {
        border-color: var(--accent);
        background: rgba(233, 69, 96, 0.02);
    }

    .add-address-card i {
        font-size: 2.5rem;
        color: var(--accent);
        margin-bottom: 1rem;
    }

    .btn-continue {
        padding: 14px 40px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
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
            <div class="checkout-step active">
                <span class="step-number">1</span>
                <span>Address</span>
            </div>
            <div class="checkout-step-line"></div>
            <div class="checkout-step">
                <span class="step-number">2</span>
                <span>Review</span>
            </div>
            <div class="checkout-step-line"></div>
            <div class="checkout-step">
                <span class="step-number">3</span>
                <span>Payment</span>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('checkout.address') }}" method="POST" id="addressForm">
            @csrf
            <input type="hidden" name="address_id" id="selectedAddressId" value="{{ $selectedAddressId }}">

            <div class="address-grid">
                @foreach($addresses as $address)
                <div class="address-card {{ $address->id == $selectedAddressId ? 'selected' : '' }}"
                    onclick="selectAddress({{ $address->id }})">
                    @if($address->is_default)
                    <span class="default-badge">Default</span>
                    @endif
                    <span class="address-type-badge">{{ ucfirst($address->address_type) }}</span>
                    <h5>{{ $address->full_name }}</h5>
                    <div class="mobile">
                        <i class="bi bi-telephone me-1"></i>{{ $address->mobile }}
                    </div>
                    <div class="address-text">
                        {{ $address->full_address }}
                    </div>
                </div>
                @endforeach

                <a href="{{ route('account.addresses.create', ['redirect' => 'checkout.address']) }}"
                    class="add-address-card text-decoration-none">
                    <i class="bi bi-plus-circle"></i>
                    <h5 class="text-dark mb-1">Add New Address</h5>
                    <p class="text-muted mb-0">Add a new delivery address</p>
                </a>
            </div>

            @if($addresses->count() > 0)
            <div class="text-center">
                <button type="submit" class="btn btn-primary-custom btn-continue">
                    Continue to Review <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
            @endif
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function selectAddress(id) {
        document.querySelectorAll('.address-card').forEach(card => {
            card.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
        document.getElementById('selectedAddressId').value = id;
    }
</script>
@endsection