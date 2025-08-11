<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\ApiResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /*
     * Register a new user
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = JWTAuth::fromUser($user);
            return response()->json(['message' => 'Signup successful'])
                ->cookie(
                    'jwt',
                    $token,
                    60,
                    '/',
                    null,
                    true,
                    true,
                    false,
                    'Strict'
                );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /*
     * Login a user
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return ApiResponse::error('Invalid credentials');
            }
            return response()->json(['message' => 'Login successful'])
                ->cookie(
                    'jwt',
                    $token,
                    60,
                    '/',
                    null,
                    true,
                    true,
                    false,
                    'Strict'
                );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    /*
     * Logout a user
     */
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Logged out successfully'])
                ->cookie(
                    'jwt',
                    '',
                    -1,
                    '/',
                    null,
                    true,
                    true,
                    false,
                    'Strict'
                );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
