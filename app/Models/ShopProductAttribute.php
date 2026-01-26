<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopProductAttribute extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'type',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ShopProductAttributeValue::class, 'attribute_id');
    }
}
