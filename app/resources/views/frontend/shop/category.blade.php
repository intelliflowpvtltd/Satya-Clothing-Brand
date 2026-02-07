@extends('frontend.layouts.master')

@section('title', $category->name . ' - Shop')

@section('styles')
<style>
    .shop-section {
        padding: 100px 0 60px;
        min-height: 80vh;
        background: var(--bg-light);
    }

    .breadcrumb-nav {
        padding: 15px 0;
        margin-bottom: 20px;
    }

    .breadcrumb-nav a {
        color: var(--text-muted);
        transition: color 0.3s;
    }

    .breadcrumb-nav a:hover {
        color: var(--primary);
    }

    .category-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .category-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .category-header p {
        color: var(--text-muted);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .product-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        position: relative;
        height: 280px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary);
        color: #fff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .product-actions {
        position: absolute;
        top: 15px;
        right: 15px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.3s ease;
    }

    .product-card:hover .product-actions {
        opacity: 1;
        transform: translateX(0);
    }

    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: var(--primary);
        color: #fff;
    }

    .product-info {
        padding: 1.25rem;
    }

    .product-category {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .product-title {
        font-weight: 600;
        margin: 0.5rem 0;
        color: var(--text-dark);
    }

    .product-title a {
        color: inherit;
        transition: color 0.3s;
    }

    .product-title a:hover {
        color: var(--primary);
    }

    .product-price {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--primary);
    }

    .product-price .original {
        color: var(--text-muted);
        text-decoration: line-through;
        font-weight: 400;
        font-size: 0.9rem;
        margin-left: 8px;
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

    .sidebar-widget {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
    }

    .sidebar-widget h5 {
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
    }

    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-list li {
        margin-bottom: 0.5rem;
    }

    .category-list a {
        color: var(--text-dark);
        display: flex;
        justify-content: space-between;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .category-list a:hover,
    .category-list a.active {
        background: var(--bg-light);
        color: var(--primary);
    }
</style>
@endsection

@section('content')
<section class="shop-section">
    <div class="container">
        <nav class="breadcrumb-nav">
            <a href="{{ route('home') }}">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('shop') }}">Shop</a>
            <span class="mx-2">/</span>
            <span class="text-dark">{{ $category->name }}</span>
        </nav>

        <div class="category-header">
            <h1>{{ $category->name }}</h1>
            <p>{{ $category->description ?? 'Explore our ' . $category->name . ' collection' }}</p>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="sidebar-widget">
                    <h5>Categories</h5>
                    <ul class="category-list">
                        <li>
                            <a href="{{ route('shop') }}">All Products</a>
                        </li>
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('shop.category', $cat->slug) }}"
                                class="{{ $cat->id === $category->id ? 'active' : '' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Products -->
            <div class="col-lg-9">
                @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                            <a href="{{ route('shop.product', $product->slug) }}">
                                <img src="{{ $product->images->first() 
                                            ? asset('storage/' . $product->images->first()->image_path) 
                                            : asset('images/placeholder.jpg') }}"
                                    alt="{{ $product->name }}">
                            </a>
                            @if($product->is_on_sale)
                            <span class="product-badge">Sale</span>
                            @endif
                            <div class="product-actions">
                                <form action="{{ route('wishlist.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="action-btn" title="Add to Wishlist">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">{{ $category->name }}</div>
                            <h6 class="product-title">
                                <a href="{{ route('shop.product', $product->slug) }}">{{ $product->name }}</a>
                            </h6>
                            <div class="product-price">
                                @if($product->is_on_sale)
                                ₹{{ number_format($product->sale_price, 0) }}
                                <span class="original">₹{{ number_format($product->price, 0) }}</span>
                                @else
                                ₹{{ number_format($product->price, 0) }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-box-seam"></i>
                    <h4>No Products Found</h4>
                    <p class="text-muted mb-4">There are no products in this category yet.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary-custom px-4">
                        Browse All Products
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection