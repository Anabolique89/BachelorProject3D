<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'role',
        'status',
        'avatar',
        'last_login_at',
        'created_by',
        'updated_by',
        'user_level_id',
        'total_tokens',
        'used_tokens',
        'available_tokens',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function userLevel()
    {
        return $this->belongsTo(UserLevel::class);
    }


    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }


    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
 * Get the cart items for this customer
 */
public function cartItems(): HasMany
{
    return $this->hasMany(CartItem::class);
}

/**
 * Get the orders for this customer
 */
public function orders(): HasMany
{
    return $this->hasMany(Order::class);
}
}
