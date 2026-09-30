<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'status' => $this->status,
            'sale' => $this->sale,
            'company' => $this->company,
            'images' => $this->images,
            'detal' => $this->detail,
            'brand' => $this->brand?->name,
            'category' => $this->category?->name,
            'owner' => $this->user?->name
        ];
    }
}
