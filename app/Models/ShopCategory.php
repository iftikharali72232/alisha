<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopCategory extends Model
{
    protected $fillable = [
        'shop_id',
        'parent_id',
        'name',
        'slug',
        'image',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ShopCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ShopCategory::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(ShopProduct::class, 'category_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? \Storage::url($this->image) : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }
}
