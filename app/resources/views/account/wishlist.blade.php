@extends('frontend.layouts.master')

@section('title', 'My Wishlist')

@section('styles')
<style>
    .wishlist-section {
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

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .wishlist-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .wishlist-image {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .wishlist-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .wishlist-card:hover .wishlist-image img {
        transform: scale(1.1);
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: none;
        color: #dc3545;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .remove-btn:hover {
        background: #dc3545;
        color: #fff;
    }

    .wishlist-info {
        padding: 1.25rem;
    }

    .wishlist-category {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .wishlist-title {
        font-weight: 600;
        margin: 0.5rem 0;
        color: var(--text-dark);
    }

    .wishlist-title:hover {
        color: var(--accent);
    }

    .wishlist-price {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .wishlist-price .original {
        color: var(--text-muted);
        text-decoration: line-through;
        font-weight: 400;
        font-size: 0.9rem;
        margin-left: 8px;
    }

    .variant-select {
        margin-bottom: 1rem;
    }

    .variant-select select {
        width: 100%;
        padding: 10px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
    }

    .btn-move-cart {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
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

    .out-of-stock {
        color: #dc3545;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<section class="wishlist-section">
    <div class="container">
        <div class="section-header">
            <h1><i class="bi bi-heart me-2"></i>My Wishlist</h1>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($wishlistItems->count() > 0)
        <div class="wishlist-grid">
            @foreach($wishlistItems as $item)
            <div class="wishlist-card">
                <div class="wishlist-image">
                    <a href="{{ route('shop.product', $item->product->slug) }}">
                        <img src="{{ $item->product->images->first() 
                                    ? asset('storage/' . $item->product->images->first()->image_path) 
                                    : asset('images/placeholder.jpg') }}"
                            alt="{{ $item->product->name }}">
                    </a>
                    <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-btn" title="Remove">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </form>
                </div>
                <div class="wishlist-info">
                    <div class="wishlist-category">{{ $item->product->category->name ?? 'Uncategorized' }}</div>
                    <h6 class="wishlist-title">
                        <a href="{{ route('shop.product', $item->product->slug) }}">{{ $item->product->name }}</a>
                    </h6>
                    <div class="wishlist-price">
                        @if($item->product->is_on_sale)
                        ₹{{ number_format($item->product->sale_price, 0) }}
                        <span class="original">₹{{ number_format($item->product->price, 0) }}</span>
                        @else
                        ₹{{ number_format($item->product->price, 0) }}
                        @endif
                    </div>

                    @if($item->product->variants->where('stock_quantity', '>', 0)->count() > 0)
                    <form action="{{ route('wishlist.move', $item->id) }}" method="POST">
                        @csrf
                        <div class="variant-select">
                            <select name="variant_id" required>
                                <option value="">Select Size/Color</option>
                                @foreach($item->product->variants->where('stock_quantity', '>', 0) as $variant)
                                <option value="{{ $variant->id }}">
                                    {{ $variant->size }} / {{ $variant->color }}
                                    ({{ $variant->stock_quantity }} left)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary-custom btn-move-cart">
                            <i class="bi bi-cart-plus me-2"></i>Move to Cart
                        </button>
                    </form>
                    @else
                    <div class="out-of-stock">
                        <i class="bi bi-exclamation-circle me-1"></i>Out of Stock
                    </div>
                    <a href="{{ route('shop.product', $item->product->slug) }}" class="btn btn-outline-primary w-100">
                        View Product
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-heart"></i>
            <h4>Your Wishlist is Empty</h4>
            <p class="text-muted mb-4">Save items you love to your wishlist.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary-custom px-4">
                Explore Products <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection