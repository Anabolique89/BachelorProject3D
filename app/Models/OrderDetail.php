<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country_code',
    ];

    /**
     * Get the order that owns the details
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the country
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute(): string
    {
        $address = $this->address1;
        if ($this->address2) {
            $address .= ', ' . $this->address2;
        }
        $address .= ', ' . $this->city;
        if ($this->state) {
            $address .= ', ' . $this->state;
        }
        $address .= ' ' . $this->zipcode;

        return $address;
    }
}
