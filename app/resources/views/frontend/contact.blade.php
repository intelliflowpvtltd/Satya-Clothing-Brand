@extends('frontend.layouts.master')

@section('title', 'Contact Us')

@section('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        padding: 80px 0;
    }

    .contact-hero h1 {
        font-size: 3rem;
        font-weight: 700;
    }

    .contact-section {
        padding: 80px 0;
    }

    .contact-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 40px;
        height: 100%;
    }

    .contact-info-card {
        background: var(--primary);
        color: #fff;
        border-radius: 20px;
        padding: 40px;
        height: 100%;
    }

    .contact-info-item {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .contact-info-item i {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .contact-info-item h5 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .contact-info-item p {
        margin-bottom: 0;
        opacity: 0.8;
    }

    .social-links-contact a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: #fff;
        margin-right: 10px;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .social-links-contact a:hover {
        background: var(--accent);
    }

    .form-control,
    .form-select {
        padding: 15px 20px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(233, 69, 96, 0.1);
    }

    .map-section {
        height: 400px;
        background: var(--bg-light);
    }

    .map-section iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<div class="contact-hero text-center">
    <div class="container">
        <h1>Contact Us</h1>
        <p class="lead mb-0">We'd love to hear from you</p>
    </div>
</div>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <h3 class="mb-4">Get in Touch</h3>
                    <p class="mb-4 opacity-75">Have questions? Need help with your order? We're here to assist you.</p>

                    <div class="contact-info-item">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <h5>Address</h5>
                            <p>123 Fashion Street, Mumbai<br>Maharashtra 400001, India</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i class="bi bi-telephone"></i>
                        <div>
                            <h5>Phone</h5>
                            <p>+91 98765 43210</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i class="bi bi-envelope"></i>
                        <div>
                            <h5>Email</h5>
                            <p>support@fashionhub.com</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i class="bi bi-clock"></i>
                        <div>
                            <h5>Hours</h5>
                            <p>Mon - Sat: 10AM - 8PM<br>Sun: 11AM - 6PM</p>
                        </div>
                    </div>

                    <div class="social-links-contact mt-4">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-card">
                    <h3 class="mb-4">Send Us a Message</h3>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Your Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <select name="subject" class="form-select @error('subject') is-invalid @enderror" required>
                                    <option value="">Select a topic</option>
                                    <option value="Order Inquiry" {{ old('subject') === 'Order Inquiry' ? 'selected' : '' }}>Order Inquiry</option>
                                    <option value="Product Question" {{ old('subject') === 'Product Question' ? 'selected' : '' }}>Product Question</option>
                                    <option value="Returns & Refunds" {{ old('subject') === 'Returns & Refunds' ? 'selected' : '' }}>Returns & Refunds</option>
                                    <option value="Feedback" {{ old('subject') === 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                    <option value="Other" {{ old('subject') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                                    required>{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="bi bi-send me-2"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<div class="map-section">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241317.11609823277!2d72.74109995709657!3d19.08219783958221!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c6306644edc1%3A0x5da4ed8f8d648c69!2sMumbai%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin"
        allowfullscreen="" loading="lazy"></iframe>
</div>
@endsection