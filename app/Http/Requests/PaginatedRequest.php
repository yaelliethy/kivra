<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginatedRequest extends FormRequest
{
    public function rules()
    {
        return [
            'page_number' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1',
        ];
    }
}
