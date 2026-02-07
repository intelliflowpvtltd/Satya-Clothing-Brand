@extends('frontend.layouts.master')

@section('title', 'Shop')

@section('styles')
<style>
    .shop-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        padding: 60px 0;
    }

    .shop-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
    }

    /* Filters Sidebar */
    .filter-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-bottom: 20px;
    }

    .filter-card h5 {
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .filter-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .filter-list li {
        margin-bottom: 10px;
    }

    .filter-list label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .filter-list input[type="checkbox"] {
        margin-right: 10px;
    }

    .price-range {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .price-range input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
    }

    /* Sort Bar */
    .sort-bar {
        background: #fff;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .view-toggle .btn {
        padding: 8px 12px;
        color: var(--text-muted);
        border: 1px solid var(--border-color);
    }

    .view-toggle .btn.active {
        color: var(--accent);
        border-color: var(--accent);
    }

    /* Product Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    @media (max-width: 991px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }

    /* Pagination */
    .pagination .page-link {
        border: none;
        padding: 10px 18px;
        margin: 0 3px;
        border-radius: 8px;
        color: var(--text);
    }

    .pagination .page-link:hover {
        background: var(--bg-light);
    }

    .pagination .page-item.active .page-link {
        background: var(--accent);
        color: #fff;
    }

    /* Color Swatches */
    .color-swatch {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        display: inline-block;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #ddd;
        cursor: pointer;
    }

    .color-swatch:hover,
    .color-swatch.active {
        box-shadow: 0 0 0 2px var(--accent);
    }

    /* Size buttons */
    .size-btn {
        padding: 5px 15px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        background: #fff;
        cursor: pointer;
    }

    .size-btn:hover,
    .size-btn.active {
        border-color: var(--accent);
        color: var(--accent);
    }
</style>
@endsection

@section('content')
<!-- Shop Header -->
<div class="shop-header">
    <div class="container">
        <h1>Shop</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item active text-white">Shop</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <form action="{{ route('shop') }}" method="GET" id="filterForm">
                    <!-- Search -->
                    <div class="filter-card">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                            <button class="btn btn-primary-custom" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="filter-card">
                        <h5>Categories</h5>
                        <ul class="filter-list">
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('shop.category', $category->slug) }}"
                                    class="{{ request('category') === $category->slug ? 'text-danger fw-bold' : '' }}">
                                    {{ $category->name }}
                                    <span class="text-muted">({{ $category->products_count }})</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-card">
                        <h5>Price Range</h5>
                        <div class="price-range">
                            <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                            <span>-</span>
                            <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-primary w-100 mt-3">Apply</button>
                    </div>

                    <!-- Size -->
                    <div class="filter-card">
                        <h5>Size</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($sizes as $size)
                            <button type="button" class="size-btn {{ request('size') === $size ? 'active' : '' }}"
                                onclick="document.querySelector('input[name=size]').value='{{ $size }}';document.getElementById('filterForm').submit();">
                                {{ $size }}
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="size" value="{{ request('size') }}">
                    </div>

                    <!-- Sale -->
                    <div class="filter-card">
                        <label class="d-flex align-items-center">
                            <input type="checkbox" name="sale" value="1" {{ request('sale') ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="ms-2">On Sale Only</span>
                        </label>
                    </div>

                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100">Clear Filters</a>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Sort Bar -->
                <div class="sort-bar d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <select name="sort" class="form-select" style="width: auto;"
                            onchange="window.location.href='{{ route('shop') }}?'+new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), sort: this.value}).toString()">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name: A-Z</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Popular</option>
                        </select>
                    </div>
                </div>

                @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                    <div class="product-card shadow-sm">
                        <div class="product-image">
                            @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}">
                            @else
                            <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400" alt="{{ $product->name }}">
                            @endif
                            @if($product->is_on_sale)
                            <span class="product-badge badge-sale">
                                -{{ $product->discount_percentage }}%
                            </span>
                            @elseif($product->created_at->diffInDays() < 14)
                                <span class="product-badge badge-new">New</span>
                                @endif
                                <div class="product-actions">
                                    <button class="btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                    <button class="btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                    <button class="btn" title="Add to Cart"><i class="bi bi-bag-plus"></i></button>
                                </div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name ?? '' }}</div>
                            <a href="{{ route('shop.product', $product->slug) }}" class="product-title d-block">{{ $product->name }}</a>
                            <div class="product-price">
                                ₹{{ number_format($product->is_on_sale ? $product->sale_price : $product->price, 0) }}
                                @if($product->is_on_sale)
                                <span class="original">₹{{ number_format($product->price, 0) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->withQueryString()->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
                    <h3>No products found</h3>
                    <p class="text-muted">Try adjusting your filters or search terms</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary-custom">View All Products</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection