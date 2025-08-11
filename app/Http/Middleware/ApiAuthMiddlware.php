<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuthMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Get the token from the cookie
        $token = $request->cookie('jwt');
        if(!$token){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        try{
            $user = JWTAuth::setToken($token)->authenticate();
            $request->merge(['user' => $user]);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
