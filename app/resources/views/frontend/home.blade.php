@extends('frontend.layouts.master')

@section('title', 'Home')

@section('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(233, 69, 96, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .hero-image {
        position: relative;
        z-index: 1;
    }

    .hero-image img {
        max-height: 500px;
        object-fit: contain;
    }

    /* Category Cards */
    .category-card {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 1/1.2;
    }

    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .category-card:hover img {
        transform: scale(1.1);
    }

    .category-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        color: #fff;
    }

    .category-overlay h4 {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    /* Features */
    .feature-box {
        text-align: center;
        padding: 30px 20px;
    }

    .feature-box i {
        font-size: 3rem;
        color: var(--accent);
        margin-bottom: 1rem;
    }

    .feature-box h5 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, var(--accent) 0%, #ff7aa2 100%);
        padding: 80px 0;
        color: #fff;
    }

    .cta-section h2 {
        font-size: 2.5rem;
        font-weight: 700;
    }

    .cta-section .btn-light {
        background: #fff;
        color: var(--accent);
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 600;
    }

    .cta-section .btn-light:hover {
        background: var(--primary);
        color: #fff;
    }

    /* Sale Badge */
    .sale-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--accent);
        color: #fff;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Discover Your <span style="color: var(--accent);">Perfect Style</span></h1>
                <p class="hero-subtitle">
                    Explore our curated collection of premium fashion pieces.
                    From casual comfort to elegant evening wear, find your signature look.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('shop') }}" class="btn btn-primary-custom">
                        Shop Now <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('shop') }}?sale=1" class="btn btn-outline-light rounded-pill px-4">
                        View Sale
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-image text-center">
                    <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600"
                        alt="Fashion" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="feature-box">
                    <i class="bi bi-truck"></i>
                    <h5>Free Shipping</h5>
                    <p class="text-muted small mb-0">On orders over ₹999</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-box">
                    <i class="bi bi-arrow-repeat"></i>
                    <h5>Easy Returns</h5>
                    <p class="text-muted small mb-0">7 days return policy</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-box">
                    <i class="bi bi-shield-check"></i>
                    <h5>Secure Payment</h5>
                    <p class="text-muted small mb-0">100% secure checkout</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-box">
                    <i class="bi bi-headset"></i>
                    <h5>24/7 Support</h5>
                    <p class="text-muted small mb-0">Dedicated support</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
@if($categories->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Explore our wide range of fashion categories</p>
        </div>
        <div class="row g-4">
            @foreach($categories->take(4) as $category)
            <div class="col-md-3 col-6">
                <a href="{{ route('shop.category', $category->slug) }}" class="category-card d-block">
                    <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=400" alt="{{ $category->name }}">
                    <div class="category-overlay">
                        <h4>{{ $category->name }}</h4>
                        <small>{{ $category->products_count }} Products</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle mb-0">Handpicked styles just for you</p>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-outline-custom d-none d-md-inline-block">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="product-card shadow-sm">
                    <div class="product-image">
                        @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}">
                        @else
                        <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400" alt="{{ $product->name }}">
                        @endif
                        @if($product->is_on_sale)
                        <span class="product-badge badge-sale">Sale</span>
                        @elseif($product->created_at->diffInDays() < 14)
                            <span class="product-badge badge-new">New</span>
                            @endif
                            <div class="product-actions">
                                <button class="btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="btn" title="Add to Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="btn" title="Add to Cart"><i class="bi bi-bag-plus"></i></button>
                            </div>
                    </div>
                    <div class="product-info">
                        <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                        <a href="{{ route('shop.product', $product->slug) }}" class="product-title d-block">{{ $product->name }}</a>
                        <div class="product-price">
                            ₹{{ number_format($product->is_on_sale ? $product->sale_price : $product->price, 0) }}
                            @if($product->is_on_sale)
                            <span class="original">₹{{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2>Summer Sale is Here!</h2>
        <p class="fs-5 mb-4">Get up to 50% off on selected items. Limited time only!</p>
        <a href="{{ route('shop') }}?sale=1" class="btn btn-light">
            Shop Sale <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<!-- New Arrivals -->
@if($newArrivals->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title">New Arrivals</h2>
                <p class="section-subtitle mb-0">Fresh styles just landed</p>
            </div>
            <a href="{{ route('shop') }}?sort=newest" class="btn btn-outline-custom d-none d-md-inline-block">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($newArrivals->take(4) as $product)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="product-card shadow-sm">
                    <div class="product-image">
                        @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}">
                        @else
                        <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400" alt="{{ $product->name }}">
                        @endif
                        <span class="product-badge badge-new">New</span>
                        <div class="product-actions">
                            <button class="btn" title="Quick View"><i class="bi bi-eye"></i></button>
                            <button class="btn" title="Add to Wishlist"><i class="bi bi-heart"></i></button>
                            <button class="btn" title="Add to Cart"><i class="bi bi-bag-plus"></i></button>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                        <a href="{{ route('shop.product', $product->slug) }}" class="product-title d-block">{{ $product->name }}</a>
                        <div class="product-price">
                            ₹{{ number_format($product->is_on_sale ? $product->sale_price : $product->price, 0) }}
                            @if($product->is_on_sale)
                            <span class="original">₹{{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection