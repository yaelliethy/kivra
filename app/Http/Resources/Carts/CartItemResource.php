<?php

namespace App\Http\Resources\Carts;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Carts\CartResource;
class CartItemResource extends BaseResource
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
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
        ];
    }
}
