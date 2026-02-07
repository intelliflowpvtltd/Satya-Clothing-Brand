@extends('admin.layouts.master')

@section('title', 'Return ' . $return->return_number)

@section('breadcrumb')
<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.returns.index') }}">Returns</a></li>
    <li class="breadcrumb-item active">{{ $return->return_number }}</li>
</ol>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">{{ $return->return_number }}</h1>
    <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Return Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Return Details</h5>
                @php
                $statusColors = [
                'pending' => 'warning',
                'approved' => 'info',
                'rejected' => 'danger',
                'pickup_scheduled' => 'info',
                'picked_up' => 'primary',
                'received' => 'info',
                'qc_passed' => 'success',
                'qc_failed' => 'danger',
                'refund_processed' => 'success',
                'completed' => 'success',
                ];
                @endphp
                <span class="badge bg-{{ $statusColors[$return->status] ?? 'secondary' }} fs-6">
                    {{ ucfirst(str_replace('_', ' ', $return->status)) }}
                </span>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Return Number:</th>
                        <td><code>{{ $return->return_number }}</code></td>
                    </tr>
                    <tr>
                        <th>Order Number:</th>
                        <td>
                            <a href="{{ route('admin.orders.show', $return->order) }}">
                                {{ $return->order->order_number }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Return Reason:</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $return->return_reason)) }}</td>
                    </tr>
                    <tr>
                        <th>Customer Comments:</th>
                        <td>{{ $return->customer_comments ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Requested On:</th>
                        <td>{{ $return->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Product -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Product Being Returned</h5>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    @if($return->orderItem->product && $return->orderItem->product->primaryImage)
                    <img src="{{ asset('storage/' . $return->orderItem->product->primaryImage->image_path) }}"
                        class="rounded me-3"
                        style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px;">
                        <i class="bi bi-image text-muted fs-1"></i>
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <h5>{{ $return->orderItem->product_name }}</h5>
                        <div class="text-muted">SKU: {{ $return->orderItem->product_sku }}</div>
                        @if($return->orderItem->variant_size || $return->orderItem->variant_color)
                        <div class="mt-1">
                            @if($return->orderItem->variant_size)
                            <span class="badge bg-light text-dark">{{ $return->orderItem->variant_size }}</span>
                            @endif
                            @if($return->orderItem->variant_color)
                            <span class="badge bg-light text-dark">{{ $return->orderItem->variant_color }}</span>
                            @endif
                        </div>
                        @endif
                        <div class="mt-2">
                            <strong>Qty:</strong> {{ $return->orderItem->quantity }} × ₹{{ number_format($return->orderItem->unit_price, 0) }}
                            = <strong>₹{{ number_format($return->orderItem->total_price, 0) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Update Return</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.returns.update', $return) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Update Status</label>
                            <select name="status" class="form-select">
                                <option value="">-- No Change --</option>
                                @foreach(['pending', 'approved', 'rejected', 'pickup_scheduled', 'picked_up', 'in_transit', 'received', 'qc_passed', 'qc_failed', 'refund_processing', 'refund_processed', 'completed'] as $status)
                                <option value="{{ $status }}" {{ $return->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Refund Amount (₹)</label>
                            <input type="number" name="refund_amount" class="form-control"
                                value="{{ $return->refund_amount ?? $return->orderItem->total_price }}" step="0.01">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pickup Scheduled Date</label>
                            <input type="datetime-local" name="pickup_scheduled_at" class="form-control"
                                value="{{ $return->pickup_scheduled_at?->format('Y-m-d\TH:i') }}">
                        </div>

                        <div class="col-md-6" id="rejectionReasonDiv" style="{{ $return->status === 'rejected' ? '' : 'display:none;' }}">
                            <label class="form-label">Rejection Reason</label>
                            <input type="text" name="rejection_reason" class="form-control"
                                value="{{ $return->rejection_reason }}" placeholder="Reason for rejection">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="2"
                                placeholder="Add a note (will be timestamped)"></textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="bi bi-check-lg me-1"></i> Update Return
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notes History -->
        @if($return->admin_notes)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Admin Notes</h5>
            </div>
            <div class="card-body">
                <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $return->admin_notes }}</pre>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Customer -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Customer</h5>
            </div>
            <div class="card-body">
                @if($return->user)
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-gradient rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 50px; height: 50px;">
                        {{ strtoupper(substr($return->user->name, 0, 1)) }}
                    </div>
                    <div class="ms-3">
                        <div class="fw-semibold">{{ $return->user->name }}</div>
                        <small class="text-muted">{{ $return->user->email }}</small>
                    </div>
                </div>
                <a href="{{ route('admin.customers.show', $return->user) }}" class="btn btn-sm btn-outline-primary w-100">
                    View Customer
                </a>
                @else
                <p class="text-muted mb-0">Guest Customer</p>
                @endif
            </div>
        </div>

        <!-- Timeline -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline-simple">
                    <div class="mb-3">
                        <small class="text-muted">Requested:</small>
                        <div>{{ $return->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    @if($return->approved_at)
                    <div class="mb-3">
                        <small class="text-muted">Approved:</small>
                        <div>{{ $return->approved_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                    @if($return->pickup_scheduled_at)
                    <div class="mb-3">
                        <small class="text-muted">Pickup Scheduled:</small>
                        <div>{{ $return->pickup_scheduled_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                    @if($return->picked_up_at)
                    <div class="mb-3">
                        <small class="text-muted">Picked Up:</small>
                        <div>{{ $return->picked_up_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                    @if($return->received_at)
                    <div class="mb-3">
                        <small class="text-muted">Received:</small>
                        <div>{{ $return->received_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                    @if($return->qc_completed_at)
                    <div class="mb-3">
                        <small class="text-muted">QC Completed:</small>
                        <div>{{ $return->qc_completed_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                    @if($return->refund_processed_at)
                    <div class="mb-3">
                        <small class="text-muted">Refund Processed:</small>
                        <div>{{ $return->refund_processed_at->format('M d, Y H:i') }}</div>
                        <div class="text-success fw-semibold">₹{{ number_format($return->refund_amount, 0) }}</div>
                    </div>
                    @endif
                    @if($return->completed_at)
                    <div class="mb-3">
                        <small class="text-muted">Completed:</small>
                        <div>{{ $return->completed_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelector('select[name="status"]').addEventListener('change', function() {
        document.getElementById('rejectionReasonDiv').style.display =
            this.value === 'rejected' ? 'block' : 'none';
    });
</script>
@endpush