<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopOrder extends Model
{
    protected $fillable = [
        'shop_id',
        'customer_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_amount',
        'total',
        'coupon_code',
        'loyalty_points_used',
        'loyalty_discount',
        'loyalty_points_earned',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'notes',
        'admin_notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'loyalty_discount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ShopCustomer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShopOrderItem::class, 'order_id');
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(ShopLoyaltyTransaction::class, 'order_id');
    }

    public function couponUsage()
    {
        return $this->hasOne(ShopCouponUsage::class, 'order_id');
    }

    // Status labels with colors for UI
    public function getStatusLabelAttribute(): array
    {
        $statuses = [
            'pending' => ['label' => 'Pending', 'color' => 'yellow'],
            'confirmed' => ['label' => 'Confirmed', 'color' => 'blue'],
            'processing' => ['label' => 'Processing', 'color' => 'indigo'],
            'shipped' => ['label' => 'Shipped', 'color' => 'purple'],
            'delivered' => ['label' => 'Delivered', 'color' => 'green'],
            'cancelled' => ['label' => 'Cancelled', 'color' => 'red'],
            'refunded' => ['label' => 'Refunded', 'color' => 'gray'],
        ];
        return $statuses[$this->status] ?? ['label' => ucfirst($this->status), 'color' => 'gray'];
    }

    public function getPaymentStatusLabelAttribute(): array
    {
        $statuses = [
            'pending' => ['label' => 'Pending', 'color' => 'yellow'],
            'paid' => ['label' => 'Paid', 'color' => 'green'],
            'failed' => ['label' => 'Failed', 'color' => 'red'],
            'refunded' => ['label' => 'Refunded', 'color' => 'gray'],
        ];
        return $statuses[$this->payment_status] ?? ['label' => ucfirst($this->payment_status), 'color' => 'gray'];
    }

    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getTotalDiscountAttribute(): float
    {
        return $this->discount_amount + $this->loyalty_discount;
    }

    public function getFullBillingAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->billing_address,
            $this->billing_city,
            $this->billing_state,
            $this->billing_postal_code,
            $this->billing_country,
        ]));
    }

    public function getFullShippingAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->shipping_address,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_postal_code,
            $this->shipping_country,
        ]));
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function cancel(): void
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('This order cannot be cancelled.');
        }

        // Restore inventory
        foreach ($this->items as $item) {
            if ($item->product && $item->product->track_inventory) {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        // Restore loyalty points if used
        if ($this->loyalty_points_used > 0 && $this->customer) {
            $this->customer->addLoyaltyPoints($this->loyalty_points_used, $this, 'Points restored from cancelled order');
        }

        $this->update(['status' => 'cancelled']);
    }

    public function markAsShipped(): void
    {
        $this->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        // Award loyalty points on delivery
        if ($this->customer && $this->loyalty_points_earned > 0) {
            $this->customer->addLoyaltyPoints($this->loyalty_points_earned, $this, 'Points earned from order delivery');
        }
    }

    // Generate unique order number
    public static function generateOrderNumber(int $shopId): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $count = static::where('shop_id', $shopId)
            ->whereDate('created_at', today())
            ->count() + 1;
        
        return $prefix . '-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber($order->shop_id);
            }
        });

        static::created(function ($order) {
            // Reduce inventory for each item
            foreach ($order->items as $item) {
                if ($item->product && $item->product->track_inventory) {
                    $item->product->decrement('quantity', $item->quantity);
                    $item->product->increment('order_count');
                }
            }
        });
    }
}
