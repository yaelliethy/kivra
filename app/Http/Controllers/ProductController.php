<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\Products\ProductResource;
use Illuminate\Support\Facades\Log;
use App\Exceptions\UserException;
use App\Http\Requests\ForcedPaginationRequest;

class ProductController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ForcedPaginationRequest $request)
    {
        try{
            $request->validateMaxPerPage(50);
            $products = $this->productRepository->filter($request->validated());
            return ApiResponse::success(ProductResource::collection($products));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching products");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try{
            $product = $this->productRepository->store($request->validated(), $request->user());
            return ApiResponse::success(new ProductResource($product));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while creating product");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, String $id)
    {
        try{
            $product = $this->productRepository->find($id);
            return ApiResponse::success(new ProductResource($product));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching product");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, String $id)
    {
        try{
            $product = $this->productRepository->update($request->validated(), $id, $request->user());
            return ApiResponse::success(new ProductResource($product));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while updating product");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, String $id)
    {
        try{
            $this->productRepository->destroy($id, $request->user());
            return ApiResponse::success([]);
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while deleting product");
        }
    }
}
