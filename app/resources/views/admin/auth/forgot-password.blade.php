@extends('admin.layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<h3>Forgot Password?</h3>
<p class="subtitle">Enter your email address and we'll send you a link to reset your password.</p>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('admin.password.email') }}" method="POST">
    @csrf

    <div class="form-floating mb-4">
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

    <button type="submit" class="btn btn-login btn-primary w-100 mb-3">
        <i class="bi bi-envelope me-2"></i>Send Reset Link
    </button>

    <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary w-100">
        <i class="bi bi-arrow-left me-2"></i>Back to Login
    </a>
</form>
@endsection