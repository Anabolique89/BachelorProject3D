<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    use HasFactory;

    protected $fillable = [
        'quest_type',
        'title',
        'description',
        'quest_category',
        'requirements',
        'token_reward',
        'experience_reward',
        'max_completions',
        'difficulty',
        'is_active',
        'start_date',
        'end_date',
        'icon',
    ];

    protected $casts = [
        'requirements' => 'array',
        'token_reward' => 'integer',
        'experience_reward' => 'integer',
        'max_completions' => 'integer',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
