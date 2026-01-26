<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopCoupon extends Model
{
    protected $fillable = [
        'shop_id',
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_limit_per_customer',
        'used_count',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(ShopCouponUsage::class, 'coupon_id');
    }

    public function getIsValidAttribute(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at > now()) return false;
        if ($this->ends_at && $this->ends_at < now()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function getRemainingUsesAttribute(): ?int
    {
        if (!$this->usage_limit) return null;
        return max(0, $this->usage_limit - $this->used_count);
    }

    public function getValidationError(float $orderTotal, ?int $customerId = null): ?string
    {
        if (!$this->is_active) {
            return 'This coupon is not active.';
        }

        if ($this->starts_at && $this->starts_at > now()) {
            return 'This coupon is not yet valid.';
        }

        if ($this->ends_at && $this->ends_at < now()) {
            return 'This coupon has expired.';
        }

        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return 'Minimum order amount of ' . $this->shop->currency . ' ' . number_format($this->min_order_amount, 2) . ' required.';
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return 'This coupon has reached its usage limit.';
        }

        if ($customerId && $this->usage_limit_per_customer) {
            $customerUsage = $this->usages()->where('customer_id', $customerId)->count();
            if ($customerUsage >= $this->usage_limit_per_customer) {
                return 'You have already used this coupon the maximum allowed times.';
            }
        }

        return null;
    }

    public function getFormattedValueAttribute(): string
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return $this->shop->currency . ' ' . number_format($this->value, 2);
    }

    public function canBeUsedBy(ShopCustomer $customer): bool
    {
        if (!$this->is_valid) return false;
        
        $customerUsage = $this->usages()->where('customer_id', $customer->id)->count();
        return $customerUsage < $this->usage_limit_per_customer;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;
        if ($this->type === 'percentage') {
            $discount = $orderTotal * ($this->value / 100);
            if ($this->max_discount_amount) {
                $discount = min($discount, $this->max_discount_amount);
            }
        } else {
            $discount = $this->value;
        }

        return min($discount, $orderTotal);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', strtoupper($code));
    }
}
