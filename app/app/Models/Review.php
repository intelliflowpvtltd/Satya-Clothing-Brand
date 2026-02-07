<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id',
        'rating',
        'title',
        'review_text',
        'images',
        'is_verified_purchase',
        'is_approved',
        'admin_response',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'is_verified_purchase' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}
