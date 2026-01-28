<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Shop extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'email',
        'phone',
        'whatsapp',
        'address',
        'city',
        'country',
        'currency',
        'tax_rate',
        'tax_included',
        'status',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
        'settings',
        'social_links',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'tax_included' => 'boolean',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'settings' => 'array',
        'social_links' => 'array',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sliders(): HasMany
    {
        return $this->hasMany(ShopSlider::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ShopGallery::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(ShopCategory::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(ShopBrand::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ShopProduct::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ShopOffer::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(ShopCoupon::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(ShopCustomer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ShopOrder::class);
    }

    public function loyaltySettings(): HasOne
    {
        return $this->hasOne(ShopLoyaltySetting::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ShopSubscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(ShopSubscription::class)
            ->whereIn('status', ['trial', 'active'])
            ->latest();
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ShopProductAttribute::class);
    }

    // Accessors
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return \Storage::url($this->logo);
        }
        return asset('images/logo-icon.svg');
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner ? \Storage::url($this->banner) : null;
    }

    public function getWhatsappLinkAttribute(): ?string
    {
        if (!$this->whatsapp) return null;
        $phone = preg_replace('/[^0-9]/', '', $this->whatsapp);
        return "https://wa.me/{$phone}";
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    // Helper Methods
    public function isOnTrial(): bool
    {
        return $this->subscription_status === 'trial' && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }

    public function isSubscribed(): bool
    {
        return $this->subscription_status === 'active' && 
               $this->subscription_ends_at && 
               $this->subscription_ends_at->isFuture();
    }

    public function hasAccess(): bool
    {
        return $this->isOnTrial() || $this->isSubscribed();
    }

    public function isPremium(): bool
    {
        return $this->isSubscribed();
    }

    public function getTrialDaysRemaining(): int
    {
        if (!$this->isOnTrial()) return 0;
        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    public function getSubscriptionDaysRemaining(): int
    {
        if (!$this->isSubscribed()) return 0;
        return max(0, now()->diffInDays($this->subscription_ends_at, false));
    }

    public function canAddProduct(): bool
    {
        $subscription = $this->activeSubscription;
        if (!$subscription || !$subscription->plan) return false;
        
        $maxProducts = $subscription->plan->max_products;
        if ($maxProducts === null) return true; // Unlimited
        
        return $this->products()->count() < $maxProducts;
    }

    public function canAddGalleryImage(): bool
    {
        $subscription = $this->activeSubscription;
        if (!$subscription || !$subscription->plan) return false;
        
        $maxImages = $subscription->plan->max_gallery_images;
        if ($maxImages === null) return true;
        
        return $this->galleries()->count() < $maxImages;
    }

    public function canUseLoyalty(): bool
    {
        $subscription = $this->activeSubscription;
        return $subscription && $subscription->plan && $subscription->plan->loyalty_enabled;
    }

    public function hasFeature(string $feature): bool
    {
        $subscription = $this->activeSubscription;
        return $subscription && $subscription->plan && $subscription->plan->{$feature};
    }

    public function getRemainingProductSlots(): ?int
    {
        $subscription = $this->activeSubscription;
        if (!$subscription || !$subscription->plan) return 0;
        
        $maxProducts = $subscription->plan->max_products;
        if ($maxProducts === null) return null; // Unlimited
        
        return max(0, $maxProducts - $this->products()->count());
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithValidSubscription($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('subscription_status', 'trial')
                   ->where('trial_ends_at', '>', now());
            })->orWhere(function ($q2) {
                $q2->where('subscription_status', 'active')
                   ->where('subscription_ends_at', '>', now());
            });
        });
    }

    // Boot method for automatic slug generation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            if (empty($shop->slug)) {
                $shop->slug = \Str::slug($shop->name);
                
                // Ensure unique slug
                $count = static::where('slug', 'like', $shop->slug . '%')->count();
                if ($count > 0) {
                    $shop->slug .= '-' . ($count + 1);
                }
            }
            
            // Set trial end date
            if (empty($shop->trial_ends_at)) {
                $trialDays = ShopGlobalSetting::get('default_trial_days', 30);
                $shop->trial_ends_at = now()->addDays($trialDays);
            }
        });
    }
}
