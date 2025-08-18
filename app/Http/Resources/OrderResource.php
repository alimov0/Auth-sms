<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array 
    {
        return [
            'id'           => $this->id,
            'buyer_id'     => $this->user_id,
            'product'      => new ProductResource($this->whenLoaded('product')),
            'total_amount' => (int) $this->total_amount,
            'address'      => $this->address,
            'status'       => $this->status,
            'created_at'   => $this->created_at?->toISOString(),
        ];
    }
}
