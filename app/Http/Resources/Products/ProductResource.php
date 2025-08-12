<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use App\Http\Resources\Categories\CategoryLookupResource;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Users\UserLookupResource;
class ProductResource extends BaseResource
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
            'description' => $this->description,
            'image_url' => $this->image_url,
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => new CategoryLookupResource($this->category),
            'seller' => $this->user ? new UserLookupResource($this->user) : null,
            'created_at' => $this->created_at,
        ];
    }
}
