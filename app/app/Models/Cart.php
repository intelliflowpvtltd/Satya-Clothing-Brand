<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'quantity',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Get line item total
     */
    public function getTotalAttribute(): float
    {
        return $this->variant->final_price * $this->quantity;
    }

    /**
     * Check if cart item is still valid (in stock)
     */
    public function isValid(): bool
    {
        return $this->product->status === 'active'
            && $this->variant->stock_quantity >= $this->quantity;
    }

    /**
     * Update quantity with stock validation
     */
    public function updateQuantity(int $quantity): bool
    {
        if ($quantity <= 0) {
            $this->delete();
            return true;
        }

        if ($this->variant->stock_quantity >= $quantity) {
            $this->update(['quantity' => $quantity]);
            return true;
        }

        return false;
    }
}
