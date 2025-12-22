<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'level_name',
        'min_experience',
        'max_experience',
        'token_multiplier',
        'perks',
        'icon',
    ];

    protected $casts = [
        'perks' => 'array',
        'token_multiplier' => 'decimal:2',
    ];
}