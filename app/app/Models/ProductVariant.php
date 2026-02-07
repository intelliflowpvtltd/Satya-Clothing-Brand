<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_code',
        'stock_quantity',
        'low_stock_threshold',
        'price_adjustment',
        'variant_sku',
    ];

    protected function casts(): array
    {
        return [
            'price_adjustment' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if variant is in stock
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Check if variant has low stock
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity > 0 && $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Get variant display name
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->size} / {$this->color}";
    }

    /**
     * Get final price for this variant
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->product->sale_price + $this->price_adjustment;
    }

    /**
     * Reduce stock by quantity
     */
    public function reduceStock(int $quantity): bool
    {
        if ($this->stock_quantity >= $quantity) {
            $this->decrement('stock_quantity', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Add stock
     */
    public function addStock(int $quantity): void
    {
        $this->increment('stock_quantity', $quantity);
    }
}
