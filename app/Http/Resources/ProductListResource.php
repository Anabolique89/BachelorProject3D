<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'display_price' => $this->display_price,
            'is_on_sale' => $this->is_on_sale,
            'discount_percentage' => $this->discount_percentage,
            'stock_quantity' => $this->stock_quantity,
            'is_in_stock' => $this->is_in_stock,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ],
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
