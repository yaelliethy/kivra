<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Responses\ApiResponse;
class UserException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        Log::error($this->getMessage());
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        return ApiResponse::error($this->getMessage(), 400);
    }
}
