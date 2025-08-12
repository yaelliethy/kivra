<?php

namespace App\Http\Responses;
use App\Http\Resources\BaseCollectionResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
class ApiResponse extends Response
{
    public static function success($data, $message = 'Success', $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status' => $status,
        ];
        if($data instanceof BaseCollectionResource){
            $response = array_merge($response, $data->toArray(Request::createFromGlobals()));
        }else{
            $response['data'] = $data;
        }
        return new Response($response, $status);
    }
    public static function error($messages, $status = 400)
    {
        return new Response([
            'success' => false,
            'messages' => $messages,
            'status' => $status,
        ], $status);
    }
}
