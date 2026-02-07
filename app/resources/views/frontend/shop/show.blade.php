@extends('frontend.layouts.master')

@section('title', $product->name)

@section('meta_description', $product->meta_description ?? Str::limit($product->description, 160))

@section('styles')
<style>
    .product-gallery {
        position: sticky;
        top: 100px;
    }
    
    .main-image {
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 15px;
        background: var(--bg-light);
    }
    
    .main-image img {
        width: 100%;
        height: 500px;
        object-fit: cover;
    }
    
    .thumbnails {
        display: flex;
        gap: 10px;
        overflow-x: auto;
    }
    
    .thumbnail {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .thumbnail:hover, .thumbnail.active {
        border-color: var(--accent);
    }
    
    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .product-price {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--accent);
    }
    
    .product-price .original {
        font-size: 1.25rem;
        color: var(--text-muted);
        text-decoration: line-through;
        font-weight: 400;
        margin-left: 10px;
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .stars {
        color: #ffc107;
    }
    
    .size-options, .color-options {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .size-option {
        padding: 10px 20px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .size-option:hover, .size-option.active {
        border-color: var(--accent);
        background: var(--accent);
        color: #fff;
    }
    
    .size-option.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    
    .color-option {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px var(--border-color);
        transition: all 0.3s ease;
    }
    
    .color-option:hover, .color-option.active {
        box-shadow: 0 0 0 3px var(--accent);
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        overflow: hidden;
        width: fit-content;
    }
    
    .quantity-control button {
        width: 45px;
        height: 45px;
        border: none;
        background: transparent;
        font-size: 1.25rem;
    }
    
    .quantity-control input {
        width: 60px;
        border: none;
        text-align: center;
        font-weight: 600;
    }
    
    .add-to-cart-section {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .btn-add-cart {
        flex: 1;
        min-width: 200px;
    }
    
    .btn-wishlist {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--border-color);
        background: transparent;
        transition: all 0.3s ease;
    }
    
    .btn-wishlist:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }
    
    .product-meta {
        border-top: 1px solid var(--border-color);
        padding-top: 20px;
        margin-top: 20px;
    }
    
    .product-meta span {
        display: block;
        margin-bottom: 8px;
        color: var(--text-muted);
    }
    
    .product-meta strong {
        color: var(--text);
    }
    
    /* Tabs */
    .product-tabs .nav-tabs {
        border: none;
        gap: 10px;
    }
    
    .product-tabs .nav-link {
        border: none;
        background: var(--bg-light);
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 500;
        color: var(--text);
    }
    
    .product-tabs .nav-link.active {
        background: var(--accent);
        color: #fff;
    }
    
    .tab-content {
        padding: 30px 0;
    }
    
    /* Reviews */
    .review-card {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    
    .review-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--accent);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .verified-badge {
        background: #28a745;
        color: #fff;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 50px;
    }
    
    /* Related Products */
    .related-section {
        background: var(--bg-light);
        padding: 60px 0;
    }
</style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Product Details -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Product Gallery -->
                <div class="col-lg-6 mb-4">
                    <div class="product-gallery">
                        <div class="main-image">
                            @if($product->primaryImage)
                                <img id="mainImage" src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}">
                            @else
                                <img id="mainImage" src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=800" alt="{{ $product->name }}">
                            @endif
                        </div>
                        @if($product->images->count() > 1)
                            <div class="thumbnails">
                                @foreach($product->images as $index => $image)
                                    <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                         onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', this)">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-category text-muted text-uppercase mb-2">{{ $product->category->name ?? 'Uncategorized' }}</div>
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    <!-- Rating -->
                    <div class="product-rating mb-3">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <span>({{ $reviews->count() }} Reviews)</span>
                    </div>
                    
                    <!-- Price -->
                    <div class="product-price mb-4">
                        ₹{{ number_format($product->sale_price ?? $product->price, 0) }}
                        @if($product->sale_price)
                            <span class="original">₹{{ number_format($product->price, 0) }}</span>
                            <span class="badge bg-danger ms-2">{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF</span>
                        @endif
                    </div>
                    
                    <!-- Short Description -->
                    <p class="text-muted mb-4">{{ Str::limit($product->description, 200) }}</p>
                    
                    <!-- Size Selection -->
                    @php
                        $sizes = $product->variants->pluck('size')->unique()->filter();
                    @endphp
                    @if($sizes->count() > 0)
                        <div class="mb-4">
                            <h6 class="mb-3 fw-semibold">Size</h6>
                            <div class="size-options">
                                @foreach($sizes as $size)
                                    @php
                                        $available = $product->variants->where('size', $size)->where('stock_quantity', '>', 0)->count() > 0;
                                    @endphp
                                    <div class="size-option {{ !$available ? 'disabled' : '' }}" data-size="{{ $size }}">{{ $size }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Color Selection -->
                    @php
                        $colors = $product->variants->pluck('color')->unique()->filter();
                    @endphp
                    @if($colors->count() > 0)
                        <div class="mb-4">
                            <h6 class="mb-3 fw-semibold">Color</h6>
                            <div class="color-options">
                                @foreach($colors as $color)
                                    <div class="color-option" 
                                         data-color="{{ $color }}" 
                                         style="background-color: {{ $color }}"
                                         title="{{ $color }}"></div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Quantity & Add to Cart -->
                    <div class="mb-4">
                        <h6 class="mb-3 fw-semibold">Quantity</h6>
                        <div class="add-to-cart-section">
                            <div class="quantity-control">
                                <button type="button" onclick="updateQuantity(-1)">-</button>
                                <input type="number" id="quantity" value="1" min="1" max="10" readonly>
                                <button type="button" onclick="updateQuantity(1)">+</button>
                            </div>
                            <button class="btn btn-primary-custom btn-add-cart">
                                <i class="bi bi-bag-plus me-2"></i> Add to Cart
                            </button>
                            <button class="btn-wishlist">
                                <i class="bi bi-heart fs-5"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Meta -->
                    <div class="product-meta">
                        <span><strong>SKU:</strong> {{ $product->sku }}</span>
                        <span><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</span>
                        <span><strong>Availability:</strong> 
                            @if($product->stock_quantity > 0)
                                <span class="text-success">In Stock</span>
                            @else
                                <span class="text-danger">Out of Stock</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Tabs Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="product-tabs">
                <ul class="nav nav-tabs justify-content-center mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#description">Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#reviews">Reviews ({{ $reviews->count() }})</a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description">
                        <div class="bg-white rounded-4 p-4 shadow-sm">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                    
                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews">
                        @if($reviews->count() > 0)
                            @foreach($reviews as $review)
                                <div class="review-card">
                                    <div class="review-header">
                                        <div class="reviewer-avatar">{{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}</div>
                                        <div>
                                            <div class="fw-semibold">{{ $review->user->name ?? 'Guest' }}</div>
                                            <div class="stars small">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($review->is_verified_purchase)
                                            <span class="verified-badge">Verified Purchase</span>
                                        @endif
                                        <small class="text-muted ms-auto">{{ $review->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <p class="mb-0">{{ $review->review }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-chat-square-text fs-1 text-muted d-block mb-3"></i>
                                <h5>No reviews yet</h5>
                                <p class="text-muted">Be the first to review this product!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="related-section">
        <div class="container">
            <h2 class="section-title text-center mb-5">You May Also Like</h2>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="product-card shadow-sm">
                            <div class="product-image">
                                @if($related->primaryImage)
                                    <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}" alt="{{ $related->name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400" alt="{{ $related->name }}">
                                @endif
                                <div class="product-actions">
                                    <button class="btn"><i class="bi bi-eye"></i></button>
                                    <button class="btn"><i class="bi bi-heart"></i></button>
                                    <button class="btn"><i class="bi bi-bag-plus"></i></button>
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="product-category">{{ $related->category->name ?? '' }}</div>
                                <a href="{{ route('shop.product', $related->slug) }}" class="product-title d-block">{{ $related->name }}</a>
                                <div class="product-price">
                                    ₹{{ number_format($related->sale_price ?? $related->price, 0) }}
                                    @if($related->sale_price)
                                        <span class="original">₹{{ number_format($related->price, 0) }}</span>
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

@push('scripts')
<script>
    function changeImage(src, thumb) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    }
    
    function updateQuantity(change) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) + change;
        if (value >= 1 && value <= 10) {
            input.value = value;
        }
    }
    
    document.querySelectorAll('.size-option:not(.disabled)').forEach(opt => {
        opt.addEventListener('click', function() {
            document.querySelectorAll('.size-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    document.querySelectorAll('.color-option').forEach(opt => {
        opt.addEventListener('click', function() {
            document.querySelectorAll('.color-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
@endpush
