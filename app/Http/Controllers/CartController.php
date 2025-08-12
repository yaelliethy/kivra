<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Http\Resources\Carts\CartResource;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginatedRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Exceptions\UserException;
use App\Http\Resources\Carts\CartDetailsResource;
class CartController extends Controller
{
    protected CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(PaginatedRequest $request)
    {
        try{
            $carts = $this->cartRepository->filter($request->validated(), $request->user());
            return ApiResponse::success(CartResource::collection($carts));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching carts");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        try{
            $cart = $this->cartRepository->store($request->validated(), $request->user());
            return ApiResponse::success(new CartResource($cart));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while creating cart");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, String $id)
    {
        try{
            $cart = $this->cartRepository->find($id, $request->user());
            return ApiResponse::success(new CartDetailsResource($cart));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching cart");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, String $id)
    {
        try{
            $cart = $this->cartRepository->update($request->validated(), $id, $request->user());
            return ApiResponse::success(new CartResource($cart));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while updating cart");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, String $id)
    {
        try{
            $this->cartRepository->destroy($id, $request->user());
            return ApiResponse::success([]);
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while deleting cart");
        }
    }
}
