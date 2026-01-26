<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShopProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'compare_price',
        'quantity',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ShopProductAttributeValue::class,
            'shop_variant_attributes',
            'variant_id',
            'attribute_value_id'
        );
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? \Storage::url($this->image) : null;
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->product->price;
    }

    public function getEffectiveComparePriceAttribute(): ?float
    {
        return $this->compare_price ?? $this->product->compare_price;
    }

    public function getIsInStockAttribute(): bool
    {
        if (!$this->product->track_inventory) return true;
        if ($this->product->allow_backorder) return true;
        return $this->quantity > 0;
    }

    public function getNameAttribute(): string
    {
        return $this->attributeValues->pluck('value')->implode(' / ');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}
