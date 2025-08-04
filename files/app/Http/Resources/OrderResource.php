<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')), // Use 'items' instead of 'orderItems'
            'subtotal' => $this->subtotal,
            'delivery_fee' => $this->delivery_fee,
            'tax' => $this->tax,
            'total_amount' => $this->total_amount,
            'delivery_address' => $this->delivery_address,
            'delivery_latitude' => $this->delivery_latitude,
            'delivery_longitude' => $this->delivery_longitude,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'delivery_notes' => $this->delivery_notes,
            'estimated_delivery_time' => $this->estimated_delivery_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
