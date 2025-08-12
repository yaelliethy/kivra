<?php

namespace App\Http\Resources\Carts;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Products\ProductResource;
class CartDetailsResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'items' => $this->cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => new ProductResource($item->product),
                    'quantity' => $item->quantity,
                ];
            }),
        ];
    }
}
