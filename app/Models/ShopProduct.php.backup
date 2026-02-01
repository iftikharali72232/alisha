<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'shop_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'compare_price',
        'cost_price',
        'quantity',
        'low_stock_threshold',
        'track_inventory',
        'allow_backorder',
        'featured_image',
        'gallery_images',
        'weight',
        'weight_unit',
        'dimensions',
        'is_featured',
        'is_active',
        'is_taxable',
        'tax_rate',
        'meta_data',
        'meta_title',
        'meta_description',
        'view_count',
        'order_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'weight' => 'decimal:2',
        'track_inventory' => 'boolean',
        'allow_backorder' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_taxable' => 'boolean',
        'gallery_images' => 'array',
        'dimensions' => 'array',
        'meta_data' => 'array',
    ];

    // Route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relationships
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ShopCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(ShopBrand::class, 'brand_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ShopProductVariant::class, 'product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ShopProductReview::class, 'product_id');
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ShopProductReview::class, 'product_id')
            ->where('status', 'approved');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(ShopOrderItem::class, 'product_id');
    }

    // Accessors
    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            if (str_starts_with($this->featured_image, 'http')) {
                return $this->featured_image;
            }
            return \Storage::url($this->featured_image);
        }
        return asset('images/placeholder-product.png');
    }

    public function getGalleryUrlsAttribute(): array
    {
        if (!$this->gallery_images) return [];
        
        return collect($this->gallery_images)->map(function ($image) {
            if (str_starts_with($image, 'http')) {
                return $image;
            }
            return \Storage::url($image);
        })->toArray();
    }

    public function getGalleriesAttribute()
    {
        if (!$this->gallery_images) return collect();
        
        return collect($this->gallery_images)->map(function ($image) {
            return (object) ['image' => $image];
        });
    }

    public function getDiscountPercentageAttribute(): ?float
    {
        if (!$this->compare_price || $this->compare_price <= $this->price) {
            return null;
        }
        return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getIsInStockAttribute(): bool
    {
        if (!$this->track_inventory) return true;
        if ($this->allow_backorder) return true;
        return $this->quantity > 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        if (!$this->track_inventory) return false;
        return $this->quantity > 0 && $this->quantity <= $this->low_stock_threshold;
    }

    public function getEffectiveTaxRateAttribute(): float
    {
        if (!$this->is_taxable) return 0;
        return $this->tax_rate ?? $this->shop->tax_rate ?? 0;
    }

    public function getFinalPriceAttribute(): float
    {
        // Check for active offers
        $offer = $this->getActiveOffer();
        if ($offer) {
            return $this->calculateOfferPrice($offer);
        }
        return $this->price;
    }

    // Methods
    public function getActiveOffer(): ?ShopOffer
    {
        return ShopOffer::where('shop_id', $this->shop_id)
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->where(function ($query) {
                $query->where('applies_to', 'all')
                    ->orWhere(function ($q) {
                        $q->where('applies_to', 'products')
                          ->whereJsonContains('applicable_ids', $this->id);
                    })
                    ->orWhere(function ($q) {
                        $q->where('applies_to', 'categories')
                          ->whereJsonContains('applicable_ids', $this->category_id);
                    })
                    ->orWhere(function ($q) {
                        $q->where('applies_to', 'brands')
                          ->whereJsonContains('applicable_ids', $this->brand_id);
                    });
            })
            ->orderBy('value', 'desc')
            ->first();
    }

    public function calculateOfferPrice(ShopOffer $offer): float
    {
        if ($offer->type === 'percentage') {
            $discount = $this->price * ($offer->value / 100);
            if ($offer->max_discount_amount) {
                $discount = min($discount, $offer->max_discount_amount);
            }
            return max(0, $this->price - $discount);
        }
        
        return max(0, $this->price - $offer->value);
    }

    public function hasActiveOffer(): bool
    {
        return $this->getActiveOffer() !== null;
    }

    public function getActiveOfferAttribute(): ?ShopOffer
    {
        return $this->getActiveOffer();
    }

    public function getFinalPrice(): float
    {
        return $this->final_price;
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_inventory', false)
              ->orWhere('allow_backorder', true)
              ->orWhere('quantity', '>', 0);
        });
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('compare_price')
            ->whereColumn('compare_price', '>', 'price');
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Str::slug($product->name);
                
                // Ensure unique slug within shop
                $count = static::where('shop_id', $product->shop_id)
                    ->where('slug', 'like', $product->slug . '%')
                    ->count();
                if ($count > 0) {
                    $product->slug .= '-' . ($count + 1);
                }
            }
            
            // Generate SKU if not provided
            if (empty($product->sku)) {
                $product->sku = strtoupper(substr($product->name, 0, 3)) . '-' . time();
            }
        });
    }
}
