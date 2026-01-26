<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopSubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'trial_days',
        'max_products',
        'max_gallery_images',
        'max_sliders',
        'max_categories',
        'max_coupons',
        'max_images_per_product',
        'loyalty_enabled',
        'advanced_analytics',
        'custom_domain',
        'commission_percentage',
        'has_variations',
        'has_offers',
        'has_coupons',
        'has_loyalty',
        'has_reviews',
        'has_analytics',
        'has_priority_support',
        'features',
        'is_active',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'loyalty_enabled' => 'boolean',
        'advanced_analytics' => 'boolean',
        'custom_domain' => 'boolean',
        'has_variations' => 'boolean',
        'has_offers' => 'boolean',
        'has_coupons' => 'boolean',
        'has_loyalty' => 'boolean',
        'has_reviews' => 'boolean',
        'has_analytics' => 'boolean',
        'has_priority_support' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ShopSubscription::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function isTrialPlan(): bool
    {
        return $this->price == 0;
    }

    public function isPremium(): bool
    {
        return $this->price > 0;
    }

    public function getPriceDisplayAttribute(): string
    {
        if ($this->isTrialPlan()) {
            return 'Free Trial';
        }
        
        $period = match($this->billing_cycle) {
            'yearly' => '/year',
            'quarterly' => '/quarter',
            default => '/month',
        };
        
        return 'Rs. ' . number_format($this->price) . $period;
    }

    public function hasUnlimitedProducts(): bool
    {
        return $this->max_products === null;
    }

    public function hasUnlimitedCategories(): bool
    {
        return $this->max_categories === null;
    }
}
