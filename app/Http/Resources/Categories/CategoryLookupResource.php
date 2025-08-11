<?php

namespace App\Http\Resources\Categories;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;
class CategoryLookupResource extends BaseResource
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
        ];
    }
}
