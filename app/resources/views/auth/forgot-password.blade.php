@extends('frontend.layouts.master')

@section('title', 'Forgot Password')

@section('styles')
<style>
    .auth-section {
        min-height: 80vh;
        background: linear-gradient(135deg, var(--bg-light) 0%, #fff 100%);
        display: flex;
        align-items: center;
        padding: 100px 0 60px;
    }

    .auth-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        padding: 50px;
        max-width: 500px;
        margin: 0 auto;
        text-align: center;
    }

    .auth-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--accent) 0%, #ff7aa2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .auth-icon i {
        font-size: 2rem;
        color: #fff;
    }

    .auth-card h3 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .auth-card .subtitle {
        color: var(--text-muted);
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        text-align: left;
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

    .btn-auth {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
    }

    .auth-link {
        color: var(--accent);
        font-weight: 500;
    }

    .auth-link:hover {
        color: var(--primary);
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-icon">
                <i class="bi bi-key"></i>
            </div>
            <h3>Forgot Password?</h3>
            <p class="subtitle">Enter your email address and we'll send you a link to reset your password.</p>

            @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email" value="{{ old('email') }}"
                        placeholder="Enter your email address" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary-custom btn-auth mb-4">
                    Send Reset Link <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <p class="mb-0">
                <a href="{{ route('login') }}" class="auth-link">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
            </p>
        </div>
    </div>
</section>
@endsection