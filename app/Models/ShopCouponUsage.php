<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopCouponUsage extends Model
{
    protected $fillable = [
        'coupon_id',
        'customer_id',
        'order_id',
        'discount_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(ShopCoupon::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ShopCustomer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class);
    }
}
