<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'customer' => [
                'id' => $this->customer->id,
                'first_name' => $this->customer->first_name,
                'last_name' => $this->customer->last_name,
                'role' => $this->customer->role,
                'status' => $this->customer->status,
                'available_tokens' => $this->customer->tokens?->available_tokens ?? 0,
                'current_level' => $this->customer->tokens?->current_level ?? 1,
            ],
        ];
    }
}