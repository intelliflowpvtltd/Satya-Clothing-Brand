<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'avatar',
        'gender',
        'date_of_birth',
        'newsletter_subscribed',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'newsletter_subscribed' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User addresses
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get default address
     */
    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    /**
     * User cart items
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * User wishlist
     */
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * User orders
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * User reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * User returns
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }

    /**
     * Get cart total
     */
    public function getCartTotalAttribute(): float
    {
        return $this->cartItems->sum('total');
    }

    /**
     * Get cart item count
     */
    public function getCartCountAttribute(): int
    {
        return $this->cartItems->sum('quantity');
    }

    /**
     * Check if product is in wishlist
     */
    public function hasInWishlist(int $productId): bool
    {
        return $this->wishlist()->where('product_id', $productId)->exists();
    }

    /**
     * Get full name or email as display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: explode('@', $this->email)[0];
    }

    /**
     * Check if mobile is verified
     */
    public function hasMobileVerified(): bool
    {
        return $this->mobile_verified_at !== null;
    }

    /**
     * Record login
     */
    public function recordLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }
}
