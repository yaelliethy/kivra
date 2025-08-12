<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Responses\ApiResponse;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\CartItemRepositoryInterface;
use App\Http\Resources\Carts\CartItemResource;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Requests\PaginatedRequest;
class CartItemController extends Controller
{
    protected CartItemRepositoryInterface $cartItemRepository;

    public function __construct(CartItemRepositoryInterface $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    public function index(PaginatedRequest $request)
    {
        try{
            $cartItems = $this->cartItemRepository->filter($request->all(), $request->user());
            return ApiResponse::success(CartItemResource::collection($cartItems));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching cart items");
        }
    }

    public function details(Request $request, String $id)
    {
        try{
            $cartItem = $this->cartItemRepository->details($id, $request->user());
            return ApiResponse::success(new CartItemResource($cartItem));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching cart item details");
        }
    }
    public function addToCart(StoreCartItemRequest $request)
    {
        try{
            $cartItem = $this->cartItemRepository->addToCart($request->validated(), $request->user());
            return ApiResponse::success(new CartItemResource($cartItem));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while adding to cart");
        }
    }

    public function removeFromCart(Request $request, String $id)
    {
        try{
            $cartItem = $this->cartItemRepository->removeFromCart($id, $request->user());
            return ApiResponse::success(new CartItemResource($cartItem));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while removing from cart");
        }
    }

    public function updateQuantity(UpdateCartItemRequest $request, String $id)
    {
        try{
            $cartItem = $this->cartItemRepository->updateQuantity($id, $request->user(), $request->validated()['quantity']);
            return ApiResponse::success(new CartItemResource($cartItem));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while updating quantity");
        }
    }
}
