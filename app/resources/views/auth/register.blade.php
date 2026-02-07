@extends('frontend.layouts.master')

@section('title', 'Create Account')

@section('styles')
<style>
    .auth-section {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--bg-light) 0%, #fff 100%);
        display: flex;
        align-items: center;
        padding: 100px 0 60px;
    }

    .auth-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1000px;
        margin: 0 auto;
    }

    .auth-left {
        background: linear-gradient(135deg, var(--accent) 0%, #ff7aa2 100%);
        color: #fff;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 600px;
    }

    .auth-left h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .auth-left p {
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .auth-features {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .auth-features li {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .auth-features li i {
        margin-right: 10px;
        font-size: 1.25rem;
    }

    .auth-right {
        padding: 50px;
    }

    .auth-right h3 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .auth-right .subtitle {
        color: var(--text-muted);
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-group .form-control {
        padding: 12px 15px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .form-group .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(233, 69, 96, 0.1);
    }

    .form-group .form-control.is-invalid {
        border-color: #dc3545;
    }

    .btn-auth {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
    }

    .password-requirements {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
    }

    .password-requirements li {
        margin-bottom: 2px;
    }

    .password-requirements li.valid {
        color: #198754;
    }

    .auth-link {
        color: var(--accent);
        font-weight: 500;
    }

    .auth-link:hover {
        color: var(--primary);
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .auth-left {
            display: none;
        }

        .auth-right {
            padding: 40px 30px;
        }
    }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="auth-left">
                        <h2>Join Us Today!</h2>
                        <p>Create your account and start shopping with exclusive member benefits.</p>
                        <ul class="auth-features">
                            <li><i class="bi bi-gift"></i> Welcome discount on first order</li>
                            <li><i class="bi bi-heart"></i> Save favorites to wishlist</li>
                            <li><i class="bi bi-truck"></i> Free shipping on orders over ₹999</li>
                            <li><i class="bi bi-arrow-repeat"></i> Easy 7-day returns</li>
                            <li><i class="bi bi-bell"></i> Early access to sales</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="auth-right">
                        <h3>Create Account</h3>
                        <p class="subtitle">Fill in your details to get started</p>

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf

                            <div class="form-group">
                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Enter your full name" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="your@email.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                            id="mobile" name="mobile" value="{{ old('mobile') }}"
                                            placeholder="10-digit mobile" maxlength="10">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Min. 8 characters" required>
                                        <ul class="password-requirements">
                                            <li id="req-length">• At least 8 characters</li>
                                            <li id="req-case">• Upper & lowercase letters</li>
                                            <li id="req-number">• At least one number</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="Confirm password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" value="1" checked>
                                <label class="form-check-label" for="newsletter">
                                    Subscribe to newsletter for exclusive offers
                                </label>
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="auth-link">Terms of Service</a> and
                                    <a href="#" class="auth-link">Privacy Policy</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary-custom btn-auth">
                                Create Account <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            Already have an account?
                            <a href="{{ route('login') }}" class="auth-link">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const password = document.getElementById('password');
    password.addEventListener('input', function() {
        const val = this.value;
        document.getElementById('req-length').classList.toggle('valid', val.length >= 8);
        document.getElementById('req-case').classList.toggle('valid', /[a-z]/.test(val) && /[A-Z]/.test(val));
        document.getElementById('req-number').classList.toggle('valid', /\d/.test(val));
    });
</script>
@endsection