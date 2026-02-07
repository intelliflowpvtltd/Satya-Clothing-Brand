<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'discount_type',
        'discount_value',
        'fabric',
        'care_instructions',
        'gender',
        'meta_title',
        'meta_description',
        'is_featured',
        'is_new_arrival',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Category relationship
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Product images relationship
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    /**
     * Product variants relationship
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Reviews relationship
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get primary image
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get calculated sale price
     */
    public function getSalePriceAttribute(): float
    {
        if ($this->discount_type === 'none') {
            return $this->price;
        }

        if ($this->discount_type === 'percentage') {
            return round($this->price * (1 - $this->discount_value / 100), 2);
        }

        return max(0, $this->price - $this->discount_value);
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): float
    {
        if ($this->discount_type === 'none' || $this->price == 0) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return $this->discount_value;
        }

        return round(($this->discount_value / $this->price) * 100, 0);
    }

    /**
     * Check if product is on sale
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->discount_type !== 'none' && $this->discount_value > 0;
    }

    /**
     * Get total stock across all variants
     */
    public function getTotalStockAttribute(): int
    {
        return $this->variants()->sum('stock_quantity');
    }

    /**
     * Check if product is in stock
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->total_stock > 0;
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?? 0, 1);
    }

    /**
     * Get review count
     */
    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for new arrivals
     */
    public function scopeNewArrivals($query)
    {
        return $query->where('is_new_arrival', true);
    }

    /**
     * Available sizes for this product
     */
    public function getAvailableSizesAttribute(): array
    {
        return $this->variants()
            ->where('stock_quantity', '>', 0)
            ->pluck('size')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Available colors for this product
     */
    public function getAvailableColorsAttribute(): array
    {
        return $this->variants()
            ->where('stock_quantity', '>', 0)
            ->select('color', 'color_code')
            ->distinct()
            ->get()
            ->toArray();
    }
}
