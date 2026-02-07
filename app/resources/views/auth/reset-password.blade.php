@extends('frontend.layouts.master')

@section('title', 'Reset Password')

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
    }

    .auth-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
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
        text-align: center;
    }

    .auth-card .subtitle {
        color: var(--text-muted);
        margin-bottom: 2rem;
        text-align: center;
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

    .password-requirements li.valid {
        color: #198754;
    }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h3>Reset Password</h3>
            <p class="subtitle">Create a new secure password for your account.</p>

            @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div class="form-group">
                    <label for="email_display">Email Address</label>
                    <input type="email" class="form-control" id="email_display"
                        value="{{ $email ?? old('email') }}" disabled>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        id="password" name="password" placeholder="Enter new password" required>
                    <ul class="password-requirements">
                        <li id="req-length">• At least 8 characters</li>
                        <li id="req-case">• Upper & lowercase letters</li>
                        <li id="req-number">• At least one number</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control"
                        id="password_confirmation" name="password_confirmation"
                        placeholder="Confirm new password" required>
                </div>

                <button type="submit" class="btn btn-primary-custom btn-auth">
                    Reset Password <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>
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