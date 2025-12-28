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
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'customer' => $this->when($this->relationLoaded('customer'), [
                'id' => $this->customer->id,
                'first_name' => $this->customer->first_name,
                'last_name' => $this->customer->last_name,
                'role' => $this->customer->role,
                'status' => $this->customer->status,

                // Token fields from customers table
                'total_tokens' => $this->customer->total_tokens ?? 0,
                'available_tokens' => $this->customer->available_tokens ?? 0,
                'used_tokens' => $this->customer->used_tokens ?? 0,

                // User level relationship
                'user_level' => $this->when($this->customer->relationLoaded('userLevel'), [
                    'id' => $this->customer->userLevel?->id,
                    'name' => $this->customer->userLevel?->name ?? 'Thrall',
                    'level' => $this->customer->userLevel?->level ?? 1,
                    'required_tokens' => $this->customer->userLevel?->required_tokens ?? 0,
                ]),
            ]),
        ];
    }
}
