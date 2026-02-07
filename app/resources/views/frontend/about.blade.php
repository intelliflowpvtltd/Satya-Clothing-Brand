@extends('frontend.layouts.master')

@section('title', 'About Us')

@section('styles')
<style>
    .about-hero {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        padding: 80px 0;
    }

    .about-hero h1 {
        font-size: 3rem;
        font-weight: 700;
    }

    .story-section {
        padding: 80px 0;
    }

    .story-image {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 20px 20px 0 var(--accent);
    }

    .story-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    .values-section {
        background: var(--bg-light);
        padding: 80px 0;
    }

    .value-card {
        background: #fff;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .value-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), #ff7aa2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
    }

    .value-card h4 {
        font-weight: 600;
        margin-bottom: 15px;
    }

    .team-section {
        padding: 80px 0;
    }

    .team-card {
        text-align: center;
    }

    .team-card img {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
        border: 5px solid var(--bg-light);
    }

    .team-card h5 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .team-card p {
        color: var(--text-muted);
    }

    .stats-section {
        background: var(--primary);
        color: #fff;
        padding: 60px 0;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: var(--accent);
    }

    .stat-label {
        font-size: 1.1rem;
        opacity: 0.8;
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<div class="about-hero text-center">
    <div class="container">
        <h1>About Us</h1>
        <p class="lead mb-0">Redefining fashion, one outfit at a time</p>
    </div>
</div>

<!-- Our Story -->
<section class="story-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="story-image">
                    <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=800" alt="Our Story">
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="section-title">Our Story</h2>
                <p class="lead text-muted">Founded with a passion for fashion and a commitment to quality.</p>
                <p>
                    What started as a small boutique in 2020 has grown into a beloved destination for fashion enthusiasts.
                    We believe that great style shouldn't come at the expense of comfort or quality.
                </p>
                <p>
                    Our team carefully curates each collection, working with talented designers and sustainable suppliers
                    to bring you pieces that look good and feel even better. From everyday essentials to statement pieces,
                    we're here to help you express your unique style.
                </p>
                <p>
                    Today, we serve thousands of happy customers across India, and we're just getting started.
                    Thank you for being part of our journey!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Products</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Cities Served</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number">4.8★</div>
                    <div class="stat-label">Customer Rating</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="values-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Values</h2>
            <p class="section-subtitle">What drives us every day</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="bi bi-heart"></i></div>
                    <h4>Quality First</h4>
                    <p class="text-muted mb-0">We never compromise on quality. Every product is carefully inspected to meet our high standards.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="bi bi-globe"></i></div>
                    <h4>Sustainability</h4>
                    <p class="text-muted mb-0">We're committed to sustainable practices and work with eco-conscious suppliers.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="bi bi-people"></i></div>
                    <h4>Customer Focus</h4>
                    <p class="text-muted mb-0">Your satisfaction is our priority. We're here to help you look and feel your best.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 text-center" style="background: linear-gradient(135deg, var(--accent) 0%, #ff7aa2 100%); color: #fff;">
    <div class="container">
        <h2 class="mb-4">Ready to upgrade your wardrobe?</h2>
        <a href="{{ route('shop') }}" class="btn btn-light btn-lg rounded-pill px-5">
            Shop Now <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>
@endsection