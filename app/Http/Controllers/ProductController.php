<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Http\Requests\PaginatedRequest;
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
    public function index(PaginatedRequest $request)
    {
        try{
            $products = $this->productRepository->filter($request);
            return ApiResponse::success($products);
        }
        catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try{
            $product = $this->productRepository->store($request, $request->user());
            return ApiResponse::success($product);
        }
        catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try{
            $product = $this->productRepository->show($product);
            return ApiResponse::success($product);
        }
        catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try{
            $product = $this->productRepository->update($request, $product, $request->user());
            return ApiResponse::success($product);
        }
        catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        try{
            $this->productRepository->destroy($product, $request->user());
            return ApiResponse::success($product);
        }
        catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }
}
