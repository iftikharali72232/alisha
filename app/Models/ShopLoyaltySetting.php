<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopLoyaltySetting extends Model
{
    protected $fillable = [
        'shop_id',
        'points_per_currency',
        'points_value',
        'minimum_points_redemption',
        'maximum_discount_percentage',
        'points_expiry_days',
        'signup_bonus_points',
        'review_bonus_points',
        'referral_bonus_points',
        'birthday_bonus_points',
        'is_enabled',
    ];

    protected $casts = [
        'points_per_currency' => 'decimal:2',
        'points_value' => 'decimal:2',
        'is_enabled' => 'boolean',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function calculatePointsForPurchase(float $amount): int
    {
        return (int) floor($amount * $this->points_per_currency);
    }

    public function calculatePointsValue(int $points): float
    {
        return $points * $this->points_value;
    }

    public function getMaximumDiscount(float $orderTotal): float
    {
        return $orderTotal * ($this->maximum_discount_percentage / 100);
    }
}
