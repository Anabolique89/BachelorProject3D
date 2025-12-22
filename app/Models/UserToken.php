<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'total_tokens',
        'available_tokens',
        'spent_tokens',
        'lifetime_earned',
        'current_level',
        'experience_points',
    ];

    protected $casts = [
        'total_tokens' => 'integer',
        'available_tokens' => 'integer',
        'spent_tokens' => 'integer',
        'lifetime_earned' => 'integer',
        'current_level' => 'integer',
        'experience_points' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
