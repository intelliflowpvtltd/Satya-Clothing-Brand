@extends('frontend.layouts.master')

@section('title', 'Add New Address')

@section('styles')
<style>
    .address-section {
        padding: 100px 0 60px;
        min-height: 80vh;
        background: var(--bg-light);
    }

    .address-card {
        background: #fff;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        max-width: 700px;
        margin: 0 auto;
    }

    .address-card h2 {
        font-size: 1.5rem;
        font-weight: 700;
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

    .form-group .form-control,
    .form-group .form-select {
        padding: 12px 15px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .form-group .form-control:focus,
    .form-group .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(233, 69, 96, 0.1);
    }

    .address-type-options {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .address-type-option {
        flex: 1;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .address-type-option:hover {
        border-color: var(--accent);
    }

    .address-type-option.selected {
        border-color: var(--accent);
        background: rgba(233, 69, 96, 0.05);
    }

    .address-type-option input {
        display: none;
    }

    .address-type-option i {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }

    .btn-save-address {
        padding: 14px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
    }
</style>
@endsection

@section('content')
<section class="address-section">
    <div class="container">
        <div class="address-card">
            <h2><i class="bi bi-geo-alt me-2"></i>Add New Address</h2>

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

            <form action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect" value="{{ $redirectTo }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="full_name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name"
                                value="{{ old('full_name', Auth::user()->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="mobile" name="mobile"
                                value="{{ old('mobile', Auth::user()->mobile) }}"
                                placeholder="10-digit mobile" maxlength="10" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address_line1">Address Line 1 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address_line1" name="address_line1"
                        value="{{ old('address_line1') }}"
                        placeholder="House No, Building, Street" required>
                </div>

                <div class="form-group">
                    <label for="address_line2">Address Line 2</label>
                    <input type="text" class="form-control" id="address_line2" name="address_line2"
                        value="{{ old('address_line2') }}"
                        placeholder="Area, Colony">
                </div>

                <div class="form-group">
                    <label for="landmark">Landmark</label>
                    <input type="text" class="form-control" id="landmark" name="landmark"
                        value="{{ old('landmark') }}"
                        placeholder="Near...">
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" name="city"
                                value="{{ old('city') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="state">State <span class="text-danger">*</span></label>
                            <select class="form-select" id="state" name="state" required>
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pincode">Pincode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pincode" name="pincode"
                                value="{{ old('pincode') }}" maxlength="6" required>
                        </div>
                    </div>
                </div>

                <label class="d-block mb-2"><strong>Address Type</strong></label>
                <div class="address-type-options">
                    <label class="address-type-option selected" onclick="selectType(this)">
                        <input type="radio" name="address_type" value="home" checked>
                        <i class="bi bi-house"></i>
                        <span>Home</span>
                    </label>
                    <label class="address-type-option" onclick="selectType(this)">
                        <input type="radio" name="address_type" value="work">
                        <i class="bi bi-briefcase"></i>
                        <span>Work</span>
                    </label>
                    <label class="address-type-option" onclick="selectType(this)">
                        <input type="radio" name="address_type" value="other">
                        <i class="bi bi-geo-alt"></i>
                        <span>Other</span>
                    </label>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1"
                        {{ old('is_default') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_default">
                        Set as default address
                    </label>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary-custom btn-save-address">
                        <i class="bi bi-check-lg me-2"></i>Save Address
                    </button>
                    <a href="{{ $redirectTo === 'checkout.address' ? route('checkout.address') : route('account.addresses') }}"
                        class="btn btn-outline-dark btn-save-address">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function selectType(element) {
        document.querySelectorAll('.address-type-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        element.classList.add('selected');
        element.querySelector('input').checked = true;
    }
</script>
@endsection