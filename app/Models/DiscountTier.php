<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier_name',
        'token_cost',
        'discount_type',
        'discount_value',
        'minimum_purchase_amount',
        'max_uses_per_user',
        'valid_days',
        'description',
        'is_active',
    ];

    protected $casts = [
        'token_cost' => 'integer',
        'discount_value' => 'decimal:2',
        'minimum_purchase_amount' => 'decimal:2',
        'max_uses_per_user' => 'integer',
        'valid_days' => 'integer',
        'is_active' => 'boolean',
    ];
}
