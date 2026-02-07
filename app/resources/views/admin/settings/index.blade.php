@extends('admin.layouts.master')

@section('title', 'Settings')

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Settings</li>
</ol>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Settings</h1>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <!-- General Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-gear me-2"></i>General Settings</h5>
                </div>
                <div class="card-body">
                    @foreach($settingsGroups['general'] as $key => $field)
                    <div class="mb-3">
                        <label for="{{ $key }}" class="form-label">{{ $field['label'] }}</label>
                        @if($field['type'] === 'textarea')
                        <textarea class="form-control" id="{{ $key }}" name="{{ $key }}" rows="3">{{ $field['value'] }}</textarea>
                        @else
                        <input type="{{ $field['type'] }}" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $field['value'] }}">
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Store Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-shop me-2"></i>Store Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($settingsGroups['store'] as $key => $field)
                        <div class="col-md-6 mb-3">
                            <label for="{{ $key }}" class="form-label">{{ $field['label'] }}</label>
                            <input type="{{ $field['type'] }}" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $field['value'] }}">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-bag me-2"></i>Order Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($settingsGroups['orders'] as $key => $field)
                        <div class="col-md-6 mb-3">
                            <label for="{{ $key }}" class="form-label">{{ $field['label'] }}</label>
                            @if($field['type'] === 'boolean')
                            <select class="form-select" id="{{ $key }}" name="{{ $key }}">
                                <option value="1" {{ $field['value'] ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ !$field['value'] ? 'selected' : '' }}>Disabled</option>
                            </select>
                            @else
                            <input type="{{ $field['type'] }}" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $field['value'] }}">
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-share me-2"></i>Social Links</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($settingsGroups['social'] as $key => $field)
                        <div class="col-md-6 mb-3">
                            <label for="{{ $key }}" class="form-label">{{ $field['label'] }}</label>
                            <input type="url" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $field['value'] }}" placeholder="https://...">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-search me-2"></i>SEO Settings</h5>
                </div>
                <div class="card-body">
                    @foreach($settingsGroups['seo'] as $key => $field)
                    <div class="mb-3">
                        <label for="{{ $key }}" class="form-label">{{ $field['label'] }}</label>
                        @if($field['type'] === 'textarea')
                        <textarea class="form-control" id="{{ $key }}" name="{{ $key }}" rows="2">{{ $field['value'] }}</textarea>
                        @else
                        <input type="text" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $field['value'] }}">
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Save -->
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary-custom w-100 mb-3">
                        <i class="bi bi-check-lg me-1"></i> Save All Settings
                    </button>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Changes will take effect immediately after saving.
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection