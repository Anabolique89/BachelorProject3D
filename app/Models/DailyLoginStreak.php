<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyLoginStreak extends Model
{
    protected $fillable = [
        'customer_id',
        'current_day',
        'current_streak',
        'longest_streak',
        'last_login_date',
        'has_claimed_today',
        'total_tokens_earned',
        'total_runes_earned',
    ];

    protected $casts = [
        'last_login_date' => 'date',
        'has_claimed_today' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
