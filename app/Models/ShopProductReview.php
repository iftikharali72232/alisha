<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'customer_id',
        'order_id',
        'name',
        'email',
        'rating',
        'title',
        'review',
        'images',
        'status',
        'admin_reply',
        'is_verified_purchase',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(ShopCustomer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }
}
