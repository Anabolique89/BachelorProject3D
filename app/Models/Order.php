<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'total_price',
        'discount_amount',
        'discount_code',
        'tokens_used',
        'tokens_earned',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tokens_used' => 'integer',
        'tokens_earned' => 'integer',
    ];

    /**
     * Generate unique order number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'EC-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Get the customer that owns the order
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the order items
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the order details (shipping address)
     */
    public function details(): HasOne
    {
        return $this->hasOne(OrderDetail::class);
    }

    /**
     * Get the payment for this order
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the user who created this order
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this order
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get final amount after discount
     */
    public function getFinalAmountAttribute(): float
    {
        return max(0, $this->total_price - $this->discount_amount);
    }

    /**
     * Check if order is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, ['delivered', 'completed']);
    }

    /**
     * Scope to get orders by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get recent orders
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
