@extends('frontend.layouts.master')

@section('title', 'Login')

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
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 500px;
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
        color: var(--accent);
    }

    .auth-right {
        padding: 60px 50px;
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
        margin-bottom: 1.5rem;
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

    .input-group-text {
        background: transparent;
        border: 2px solid var(--border-color);
        border-right: none;
        border-radius: 10px 0 0 10px;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 10px 10px 0;
    }

    .btn-auth {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: var(--text-muted);
    }

    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-color);
    }

    .auth-divider span {
        padding: 0 15px;
        font-size: 0.9rem;
    }

    .auth-link {
        color: var(--accent);
        font-weight: 500;
    }

    .auth-link:hover {
        color: var(--primary);
        text-decoration: underline;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-muted);
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
                        <h2>Welcome Back!</h2>
                        <p>Sign in to continue your shopping experience with exclusive benefits.</p>
                        <ul class="auth-features">
                            <li><i class="bi bi-check-circle-fill"></i> Track your orders in real-time</li>
                            <li><i class="bi bi-check-circle-fill"></i> Save items to your wishlist</li>
                            <li><i class="bi bi-check-circle-fill"></i> Faster checkout process</li>
                            <li><i class="bi bi-check-circle-fill"></i> Exclusive member discounts</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="auth-right">
                        <h3>Sign In</h3>
                        <p class="subtitle">Enter your credentials to access your account</p>

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email">Email or Mobile</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="Enter email or mobile" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Enter password" required>
                                    </div>
                                    <span class="password-toggle" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="{{ route('password.request') }}" class="auth-link">Forgot Password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary-custom btn-auth">
                                Sign In <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>

                        <div class="auth-divider">
                            <span>or</span>
                        </div>

                        <p class="text-center mb-0">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="auth-link">Create Account</a>
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
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection