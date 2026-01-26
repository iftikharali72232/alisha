<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopBrand extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ShopProduct::class, 'brand_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? \Storage::url($this->logo) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
