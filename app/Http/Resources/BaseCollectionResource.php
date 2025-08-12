<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
class BaseCollectionResource extends ResourceCollection
{
    public function toArray($request)
    {
        $resourceClass = $this->collects ?? null;

        $data = $this->collection
            ->map(fn($item) => (new $resourceClass($item))->toArray($request))
            ->all();

        if (method_exists($this->resource, 'total')) {
            return [
                'data' => $data,
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
        return ['data' => $data];
    }
}
