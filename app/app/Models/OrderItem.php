<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'product_sku',
        'size',
        'color',
        'quantity',
        'unit_price',
        'discount',
        'tax_amount',
        'hsn_code',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
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
     * Create order item from cart item
     */
    public static function createFromCart(Cart $cartItem, Order $order): self
    {
        return self::create([
            'order_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'variant_id' => $cartItem->variant_id,
            'product_name' => $cartItem->product->name,
            'product_sku' => $cartItem->product->sku,
            'size' => $cartItem->variant->size,
            'color' => $cartItem->variant->color,
            'quantity' => $cartItem->quantity,
            'unit_price' => $cartItem->variant->final_price,
            'discount' => 0,
            'tax_amount' => 0,
            'total' => $cartItem->total,
        ]);
    }
}
