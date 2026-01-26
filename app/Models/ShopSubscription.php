<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopSubscription extends Model
{
    protected $fillable = [
        'shop_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'amount_paid',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(ShopSubscriptionPlan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('ends_at', '>', now());
    }

    public function scopeTrial($query)
    {
        return $query->where('status', 'trial')
            ->where('trial_ends_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
            $this->ends_at && 
            $this->ends_at->isFuture();
    }

    public function isOnTrial(): bool
    {
        return $this->status === 'trial' && 
            $this->trial_ends_at && 
            $this->trial_ends_at->isFuture();
    }

    public function isExpired(): bool
    {
        if ($this->status === 'trial') {
            return $this->trial_ends_at && $this->trial_ends_at->isPast();
        }
        
        return $this->ends_at && $this->ends_at->isPast();
    }

    public function isCanceled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function daysRemaining(): int
    {
        if ($this->isOnTrial()) {
            return max(0, now()->diffInDays($this->trial_ends_at, false));
        }
        
        if ($this->ends_at) {
            return max(0, now()->diffInDays($this->ends_at, false));
        }
        
        return 0;
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function activate(): void
    {
        $billingCycle = $this->plan->billing_cycle ?? 'monthly';
        $duration = match ($billingCycle) {
            'yearly' => 365,
            'quarterly' => 90,
            default => 30,
        };

        $this->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($duration),
        ]);
    }

    public function renew(): void
    {
        $this->activate();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'trial' => 'bg-blue-100 text-blue-800',
            'expired' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Active',
            'trial' => 'Trial',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }
}
