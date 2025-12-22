<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuneType extends Model
{
    use HasFactory;

    protected $fillable = [
        'rune_name',
        'rarity',
        'description',
        'spawn_rate',
        'token_value',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'spawn_rate' => 'decimal:4',
        'token_value' => 'integer',
        'is_active' => 'boolean',
    ];
}