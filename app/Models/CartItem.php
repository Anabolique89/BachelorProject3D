<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the customer that owns the cart item
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product for this cart item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the subtotal for this cart item (uses current product price)
     */
    public function getSubtotalAttribute(): float
    {
        // Use current product price (could be on sale)
        $price = $this->product->is_on_sale ? $this->product->sale_price : $this->product->price;
        return $this->quantity * $price;
    }

    /**
     * Get the price for this item
     */
    public function getPriceAttribute(): float
    {
        return $this->product->is_on_sale ? $this->product->sale_price : $this->product->price;
    }

    /**
     * Scope to get cart items for a specific customer
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}