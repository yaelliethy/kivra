<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
class BaseCollectionResource extends ResourceCollection
{
    public function toArray($request)
    {
        //Check if paginated resource
        if(method_exists($this->resource, 'total')){
            return [
                'data' => $this->collection,
                'meta' => [
                    'total' => $this->total(),
                    'per_page' => $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'last_page' => $this->lastPage(),
                    'from' => $this->firstItem(),
                    'to' => $this->lastItem(),
                ],
            ];
        }
        return [
            'data' => $this->collection,
        ];
    }
}
