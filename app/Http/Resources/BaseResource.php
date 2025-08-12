<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public static function collection($resource)
    {
        $collection = new BaseCollectionResource($resource);
        $collection->collects = static::class;
        return $collection;
    }
}
