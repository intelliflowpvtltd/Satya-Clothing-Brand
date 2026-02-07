<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'address_id',
        'subtotal',
        'discount',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'shipping_charges',
        'cod_charges',
        'total_amount',
        'coupon_id',
        'coupon_code',
        'payment_method',
        'payment_status',
        'payment_gateway',
        'transaction_id',
        'payment_details',
        'order_status',
        'courier_name',
        'awb_number',
        'shipped_at',
        'delivered_at',
        'expected_delivery_date',
        'notes',
        'admin_notes',
        'is_gift',
        'gift_message',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'cgst_amount' => 'decimal:2',
            'sgst_amount' => 'decimal:2',
            'igst_amount' => 'decimal:2',
            'shipping_charges' => 'decimal:2',
            'cod_charges' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'payment_details' => 'array',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'expected_delivery_date' => 'date',
            'is_gift' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD' . date('Ymd') . strtoupper(Str::random(5));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }

    /**
     * Get total tax amount
     */
    public function getTotalTaxAttribute(): float
    {
        return $this->cgst_amount + $this->sgst_amount + $this->igst_amount;
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }

    /**
     * Check if order can be returned
     */
    public function canBeReturned(): bool
    {
        if ($this->order_status !== 'delivered' || !$this->delivered_at) {
            return false;
        }

        // 7 day return window
        return $this->delivered_at->addDays(7)->isFuture();
    }

    /**
     * Get order status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'packed' => 'bg-primary',
            'shipped' => 'bg-primary',
            'out_for_delivery' => 'bg-info',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            'returned' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentBadgeClassAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'failed' => 'bg-danger',
            'refunded' => 'bg-info',
            'partially_refunded' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Update order status
     */
    public function updateStatus(string $status): void
    {
        $this->update(['order_status' => $status]);

        if ($status === 'shipped') {
            $this->update(['shipped_at' => now()]);
        }

        if ($status === 'delivered') {
            $this->update(['delivered_at' => now()]);
        }
    }
}
