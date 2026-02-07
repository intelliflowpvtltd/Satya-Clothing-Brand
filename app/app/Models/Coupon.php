<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_value',
        'max_discount',
        'valid_from',
        'valid_to',
        'usage_limit',
        'usage_per_customer',
        'times_used',
        'applicable_categories',
        'applicable_products',
        'auto_apply',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_order_value' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
            'applicable_categories' => 'array',
            'applicable_products' => 'array',
            'auto_apply' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if coupon is valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($now->lt($this->valid_from) || $now->gt($this->valid_to)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->times_used >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can use this coupon
     */
    public function canBeUsedBy(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $userUsageCount = $this->usages()->where('user_id', $user->id)->count();

        return $userUsageCount < $this->usage_per_customer;
    }

    /**
     * Calculate discount for given subtotal
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order_value) {
            return 0;
        }

        $discount = match ($this->discount_type) {
            'percentage' => $subtotal * ($this->discount_value / 100),
            'fixed' => $this->discount_value,
            'free_shipping' => 0, // Handled separately
            default => 0,
        };

        if ($this->max_discount !== null) {
            $discount = min($discount, $this->max_discount);
        }

        return round($discount, 2);
    }

    /**
     * Record usage of this coupon
     */
    public function recordUsage(User $user, Order $order, float $discountApplied): void
    {
        $this->usages()->create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'discount_applied' => $discountApplied,
        ]);

        $this->increment('times_used');
    }

    /**
     * Scope for active coupons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now());
    }
}
