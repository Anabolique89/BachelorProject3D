<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestCompletion extends Model
{
    protected $fillable = [
        'customer_id',
        'quest_id',
        'quest_name',
        'rewards',
        'completed_at',
    ];

    protected $casts = [
        'rewards' => 'array',
        'completed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
