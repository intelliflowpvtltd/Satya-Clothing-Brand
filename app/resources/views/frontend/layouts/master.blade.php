<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Premium fashion clothing store - Shop the latest trends')">
    <meta name="keywords" content="@yield('meta_keywords', 'clothing, fashion, online store, shopping')">
    <title>@yield('title', 'Home') | {{ config('app.name', 'E-Commerce') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #1a1a2e;
            --primary-light: #16213e;
            --accent: #e94560;
            --accent-hover: #d63151;
            --text: #333;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --border-color: #e9ecef;
            --gold: #c9a962;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text);
            background-color: #fff;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        /* Header */
        .top-bar {
            background: var(--primary);
            color: #fff;
            padding: 8px 0;
            font-size: 0.85rem;
        }

        .top-bar a {
            color: rgba(255, 255, 255, 0.8);
        }

        .top-bar a:hover {
            color: #fff;
        }

        .main-header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
        }

        .logo span {
            color: var(--accent);
        }

        .nav-link {
            color: var(--text);
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            position: relative;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--accent);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }

        .header-icons .btn {
            padding: 0.5rem;
            color: var(--text);
        }

        .header-icons .btn:hover {
            color: var(--accent);
        }

        .cart-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--accent);
            color: #fff;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 50px;
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background: var(--accent-hover);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(233, 69, 96, 0.3);
        }

        .btn-outline-custom {
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: #fff;
        }

        /* Product Card */
        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            background: var(--bg-light);
        }

        .product-image img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-actions {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            transition: bottom 0.3s ease;
        }

        .product-card:hover .product-actions {
            bottom: 0;
        }

        .product-actions .btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: var(--text);
            border: none;
        }

        .product-actions .btn:hover {
            background: var(--accent);
            color: #fff;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-sale {
            background: var(--accent);
            color: #fff;
        }

        .badge-new {
            background: var(--primary);
            color: #fff;
        }

        .product-info {
            padding: 1.25rem;
        }

        .product-category {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .product-title {
            font-weight: 600;
            color: var(--text);
            margin: 0.5rem 0;
        }

        .product-title:hover {
            color: var(--accent);
        }

        .product-price {
            font-weight: 700;
            color: var(--primary);
        }

        .product-price .original {
            color: var(--text-muted);
            text-decoration: line-through;
            font-weight: 400;
            font-size: 0.9rem;
            margin-left: 8px;
        }

        /* Footer */
        .footer {
            background: var(--primary);
            color: #fff;
            padding: 60px 0 30px;
        }

        .footer h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--gold);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-links a:hover {
            color: #fff;
            padding-left: 5px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            margin-right: 10px;
        }

        .social-links a:hover {
            background: var(--accent);
        }

        .newsletter-form .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            padding: 12px 20px;
            border-radius: 50px 0 0 50px;
        }

        .newsletter-form .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .newsletter-form .btn {
            border-radius: 0 50px 50px 0;
            padding: 12px 25px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            margin-top: 40px;
        }

        /* Utility Classes */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        @yield('styles')
    </style>
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 d-none d-md-block">
                    <i class="bi bi-truck me-2"></i> Free shipping on orders over ₹999
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="me-3"><i class="bi bi-telephone me-1"></i> +91 98765 43210</a>
                    <a href="#"><i class="bi bi-envelope me-1"></i> support@store.com</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg py-3">
                <a class="logo" href="{{ url('/') }}">Fashion<span>Hub</span></a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <i class="bi bi-list fs-3"></i>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('shop*') ? 'active' : '' }}" href="{{ route('shop') }}">Shop</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Categories</a>
                            <ul class="dropdown-menu">
                                @php
                                $navCategories = \App\Models\Category::where('is_active', true)->whereNull('parent_id')->take(8)->get();
                                @endphp
                                @foreach($navCategories as $cat)
                                <li><a class="dropdown-item" href="{{ route('shop.category', $cat->slug) }}">{{ $cat->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                        </li>
                    </ul>
                </div>

                <div class="header-icons d-flex align-items-center">
                    <button class="btn position-relative" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="bi bi-search fs-5"></i>
                    </button>
                    @auth
                    <a href="{{ route('wishlist.index') }}" class="btn">
                        <i class="bi bi-heart fs-5"></i>
                    </a>
                    <a href="{{ route('cart.index') }}" class="btn position-relative">
                        <i class="bi bi-bag fs-5"></i>
                        @if(Auth::user()->cart_count > 0)
                        <span class="cart-badge">{{ Auth::user()->cart_count }}</span>
                        @endif
                    </a>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header">
                                <strong>{{ Auth::user()->display_name }}</strong><br>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('account.orders') }}"><i class="bi bi-bag-check me-2"></i>My Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist.index') }}"><i class="bi bi-heart me-2"></i>My Wishlist</a></li>
                            <li><a class="dropdown-item" href="{{ route('account.addresses') }}"><i class="bi bi-geo-alt me-2"></i>My Addresses</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="btn">
                        <i class="bi bi-person fs-5"></i>
                    </a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <a class="logo text-white" href="{{ url('/') }}">Fashion<span>Hub</span></a>
                    <p class="mt-3 text-white-50">
                        Your ultimate destination for trendy and affordable fashion.
                        We bring you the latest styles from around the world.
                    </p>
                    <div class="social-links mt-4">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('shop') }}">Shop</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Help</h5>
                    <ul class="footer-links">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Shipping</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Track Order</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 mb-4">
                    <h5>Newsletter</h5>
                    <p class="text-white-50">Subscribe to get special offers and updates.</p>
                    <form class="newsletter-form mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email">
                            <button class="btn btn-primary-custom" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="text-white-50 mb-0">
                    © {{ date('Y') }} FashionHub. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>