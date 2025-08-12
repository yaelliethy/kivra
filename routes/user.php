<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\UserController;
Route::middleware('api_auth')->group(function () {
    Route::group(['prefix' => 'products'], function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
    Route::group(['prefix' => 'carts'], function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/{id}', [CartController::class, 'show']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
    });
    Route::group(['prefix' => 'cartItems'], function () {
        Route::get('/', [CartItemController::class, 'index']);
        Route::get('/{id}', [CartItemController::class, 'details']);
        Route::post('/', [CartItemController::class, 'addToCart']);
        Route::delete('/{id}', [CartItemController::class, 'removeFromCart']);
        Route::put('/{id}', [CartItemController::class, 'updateQuantity']);
    });
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);
    });
    Route::group(['prefix' => 'users'], function () {
        Route::put('/', [UserController::class, 'update']);
        Route::put('/updatePassword', [UserController::class, 'updatePassword']);
    });
});
