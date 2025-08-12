<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;
class UserLookupResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
