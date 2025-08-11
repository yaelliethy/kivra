<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    //Override the collection method to return a BaseCollectionResource
    public static function collection($resource)
    {
        return new BaseCollectionResource($resource);
    }
}
