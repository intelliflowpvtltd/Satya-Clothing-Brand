@extends('frontend.layouts.master')

@section('title', 'My Addresses')

@section('styles')
<style>
    .addresses-section {
        padding: 100px 0 60px;
        min-height: 80vh;
        background: var(--bg-light);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .address-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .address-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .address-type-badge {
        display: inline-block;
        padding: 4px 12px;
        background: var(--bg-light);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .address-card h5 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .address-card .mobile {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .address-card .address-text {
        color: var(--text-dark);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .default-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--primary);
        color: #fff;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .address-actions {
        display: flex;
        gap: 10px;
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
    }

    .address-actions button,
    .address-actions a {
        flex: 1;
        text-align: center;
        padding: 8px;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .add-address-card {
        background: #fff;
        border: 2px dashed var(--border-color);
        border-radius: 15px;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        min-height: 220px;
    }

    .add-address-card:hover {
        border-color: var(--accent);
        background: rgba(233, 69, 96, 0.02);
    }

    .add-address-card i {
        font-size: 2.5rem;
        color: var(--accent);
        margin-bottom: 1rem;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 15px;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<section class="addresses-section">
    <div class="container">
        <div class="section-header">
            <h1><i class="bi bi-geo-alt me-2"></i>My Addresses</h1>
            <a href="{{ route('account.addresses.create') }}" class="btn btn-primary-custom">
                <i class="bi bi-plus-lg me-2"></i>Add New Address
            </a>
        </div>

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

        @if($addresses->count() > 0)
        <div class="address-grid">
            @foreach($addresses as $address)
            <div class="address-card">
                @if($address->is_default)
                <span class="default-badge">Default</span>
                @endif
                <span class="address-type-badge">{{ ucfirst($address->address_type) }}</span>
                <h5>{{ $address->full_name }}</h5>
                <div class="mobile">
                    <i class="bi bi-telephone me-1"></i>{{ $address->mobile }}
                </div>
                <div class="address-text">
                    {{ $address->full_address }}
                </div>
                <div class="address-actions">
                    @if(!$address->is_default)
                    <form action="{{ route('account.addresses.default', $address->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-check-circle me-1"></i>Set Default
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this address?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            <a href="{{ route('account.addresses.create') }}" class="add-address-card text-decoration-none">
                <i class="bi bi-plus-circle"></i>
                <h5 class="text-dark mb-1">Add New Address</h5>
                <p class="text-muted mb-0">Add a new delivery address</p>
            </a>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-geo-alt"></i>
            <h4>No Saved Addresses</h4>
            <p class="text-muted mb-4">You haven't added any addresses yet.</p>
            <a href="{{ route('account.addresses.create') }}" class="btn btn-primary-custom px-4">
                Add Your First Address
            </a>
        </div>
        @endif
    </div>
</section>
@endsection