<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\UserException;
class ForcedPaginationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'per_page' => 'required|integer|min:1',
            'page_number' => 'required|integer|min:1',
        ];
    }
    public function validateMaxPerPage(int $maxPerPage): bool
    {
        if ($this->per_page > $maxPerPage) {
            throw new UserException('The per_page field must be less than or equal to ' . $maxPerPage);
        }
        return true;
    }
}
