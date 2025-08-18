<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array 
    {
        return [
            'id'          => $this->id,
            'owner'       => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'phone' => $this->user->phone,
            ],
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => (int) $this->price,
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
