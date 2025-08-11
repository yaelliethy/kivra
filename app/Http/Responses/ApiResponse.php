<?php

namespace App\Http\Responses;
use App\Http\Resources\BaseCollectionResource;

class ApiResponse
{
    public static function success($data, $message = 'Success', $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status' => $status,
        ];
        if($data instanceof BaseCollectionResource){
            $response = array_merge($response, $data->toArray(null));
        }else{
            $response['data'] = $data;
        }
        return response()->json($response, $status);
    }
    public static function error($messages, $status = 400)
    {
        return response()->json([
            'success' => false,
            'messages' => $messages,
            'status' => $status,
        ], $status);
    }
}
