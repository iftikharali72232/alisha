<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ShopCustomer extends Authenticatable
{
    protected $fillable = [
        'shop_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'total_orders',
        'total_spent',
        'loyalty_points',
        'last_order_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'last_order_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(ShopCustomerAddress::class, 'customer_id');
    }

    public function defaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ShopOrder::class, 'customer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ShopProductReview::class, 'customer_id');
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(ShopLoyaltyTransaction::class, 'customer_id');
    }

    public function couponUsages(): HasMany
    {
        return $this->hasMany(ShopCouponUsage::class, 'customer_id');
    }

    public function getAvatarUrlAttribute(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f43f5e&color=fff';
    }

    public function getAvailablePointsAttribute(): int
    {
        // Get non-expired points
        $shop = $this->shop;
        $loyaltySettings = $shop->loyaltySettings;
        
        if (!$loyaltySettings || !$loyaltySettings->is_enabled) {
            return 0;
        }

        $validityDays = $loyaltySettings->points_validity_days;
        
        $query = $this->loyaltyTransactions()
            ->selectRaw('SUM(CASE WHEN type = "earned" THEN points ELSE 0 END) - SUM(CASE WHEN type IN ("redeemed", "expired") THEN points ELSE 0 END) as balance');
        
        if ($validityDays) {
            $query->where(function ($q) use ($validityDays) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
        }
        
        return max(0, (int) $query->value('balance'));
    }

    public function addLoyaltyPoints(int $points, ?ShopOrder $order = null, ?string $description = null): void
    {
        $loyaltySettings = $this->shop->loyaltySettings;
        $expiresAt = null;
        
        if ($loyaltySettings && $loyaltySettings->points_validity_days) {
            $expiresAt = now()->addDays($loyaltySettings->points_validity_days);
        }

        $this->loyaltyTransactions()->create([
            'type' => 'earned',
            'points' => $points,
            'order_id' => $order?->id,
            'order_amount' => $order?->total,
            'description' => $description ?? 'Points earned from order',
            'expires_at' => $expiresAt,
        ]);

        $this->increment('loyalty_points', $points);
    }

    public function redeemLoyaltyPoints(int $points, ?ShopOrder $order = null, ?string $description = null): bool
    {
        if ($this->available_points < $points) {
            return false;
        }

        $this->loyaltyTransactions()->create([
            'type' => 'redeemed',
            'points' => $points,
            'order_id' => $order?->id,
            'description' => $description ?? 'Points redeemed for discount',
        ]);

        $this->decrement('loyalty_points', $points);
        return true;
    }

    public function updateOrderStats(): void
    {
        $this->update([
            'total_orders' => $this->orders()->count(),
            'total_spent' => $this->orders()->where('payment_status', 'paid')->sum('total'),
            'last_order_at' => $this->orders()->latest()->value('created_at'),
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
