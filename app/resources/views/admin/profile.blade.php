@extends('admin.layouts.master')

@section('title', 'My Profile')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">My Profile</h1>
    <p class="text-muted mb-0">Manage your account settings and preferences</p>
</div>

<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="profile-avatar mx-auto mb-3">
                    @if($admin->avatar)
                    <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                    <div class="avatar-initials-large">
                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                    </div>
                    @endif
                </div>
                <h4 class="mb-1">{{ $admin->name }}</h4>
                <p class="text-muted mb-2">{{ ucfirst($admin->role ?? 'Administrator') }}</p>
                <span class="badge bg-success-subtle text-success px-3 py-2">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Online
                </span>

                <hr class="my-4">

                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-envelope text-primary me-3" style="font-size: 1.1rem; width: 24px;"></i>
                        <div>
                            <small class="text-muted d-block">Email</small>
                            <span>{{ $admin->email }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-telephone text-primary me-3" style="font-size: 1.1rem; width: 24px;"></i>
                        <div>
                            <small class="text-muted d-block">Phone</small>
                            <span>{{ $admin->phone ?? 'Not set' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar3 text-primary me-3" style="font-size: 1.1rem; width: 24px;"></i>
                        <div>
                            <small class="text-muted d-block">Member Since</small>
                            <span>{{ $admin->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Forms -->
    <div class="col-lg-8">
        <!-- Update Profile Form -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $admin->phone) }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror"
                                accept="image/*">
                            @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-check2 me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar-initials-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 2.5rem;
        margin: 0 auto;
    }

    .card {
        border-radius: 16px;
    }

    .card-header {
        border-radius: 16px 16px 0 0;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.65rem 1rem;
        border-color: #e9ecef;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(128, 0, 32, 0.1);
    }

    .bg-success-subtle {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
</style>
@endpush
@endsection