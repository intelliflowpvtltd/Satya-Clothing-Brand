<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'reason',
        'description',
        'images',
        'return_method',
        'pickup_awb',
        'status',
        'rejection_reason',
        'admin_notes',
        'refund_amount',
        'refund_status',
        'refund_transaction_id',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'refund_amount' => 'decimal:2',
            'refunded_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get reason display text
     */
    public function getReasonTextAttribute(): string
    {
        return match ($this->reason) {
            'wrong_size' => 'Wrong Size/Fit',
            'damaged' => 'Damaged Product',
            'defective' => 'Defective Product',
            'not_as_described' => 'Product Not As Described',
            'changed_mind' => 'Changed Mind',
            'other' => 'Other',
            default => $this->reason,
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'requested' => 'bg-warning',
            'approved' => 'bg-info',
            'rejected' => 'bg-danger',
            'pickup_scheduled' => 'bg-primary',
            'picked_up' => 'bg-primary',
            'received' => 'bg-info',
            'qc_passed' => 'bg-success',
            'qc_failed' => 'bg-danger',
            'refund_initiated' => 'bg-info',
            'refund_completed' => 'bg-success',
            'cancelled' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Check if return can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['requested', 'approved', 'pickup_scheduled']);
    }
}
