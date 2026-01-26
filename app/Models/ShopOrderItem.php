<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopOrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'variant_name',
        'sku',
        'price',
        'quantity',
        'tax_amount',
        'discount_amount',
        'total',
        'options',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'options' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ShopProductVariant::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->variant && $this->variant->image) {
            return \Storage::url($this->variant->image);
        }
        return $this->product?->featured_image_url ?? asset('images/placeholder-product.png');
    }
}
