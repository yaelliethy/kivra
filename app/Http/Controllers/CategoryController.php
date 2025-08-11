<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Responses\ApiResponse;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Categories\CategoryLookupResource;
use App\Http\Requests\PaginatedRequest;
class CategoryController extends Controller
{
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(PaginatedRequest $request)
    {
        try{
            $categories = $this->categoryRepository->filter($request);
            return ApiResponse::success(CategoryResource::collection($categories));
        }
        catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }
    public function lookup(){
        try{
            $categories = $this->categoryRepository->all();
            return ApiResponse::success(CategoryLookupResource::collection($categories));
        }
        catch(\Exception $e){
            return ApiResponse::error($e->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
