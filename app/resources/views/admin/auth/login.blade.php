@extends('admin.layouts.auth')

@section('title', 'Admin Login')

@section('content')
<h3>Welcome Back!</h3>
<p class="subtitle">Sign in to access the admin panel.</p>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('admin.login.submit') }}" method="POST">
    @csrf

    <div class="form-floating mb-3">
        <input type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="email"
            name="email"
            placeholder="admin@example.com"
            value="{{ old('email') }}"
            required
            autofocus>
        <label for="email">Email Address</label>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-floating mb-3">
        <input type="password"
            class="form-control @error('password') is-invalid @enderror"
            id="password"
            name="password"
            placeholder="Password"
            required>
        <label for="password">Password</label>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="{{ route('admin.password.request') }}" class="link-primary-custom text-decoration-none">Forgot Password?</a>
    </div>

    <button type="submit" class="btn btn-login btn-primary w-100">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>
</form>

<div class="text-center mt-4">
    <small class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
</div>
@endsection