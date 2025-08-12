<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Responses\ApiResponse;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Categories\CategoryLookupResource;
use App\Http\Requests\PaginatedRequest;
use Illuminate\Support\Facades\Log;
use App\Exceptions\UserException;
use Illuminate\Http\Request;
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
            $categories = $this->categoryRepository->filter($request->validated());
            return ApiResponse::success(CategoryResource::collection($categories));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching categories");
        }
    }
    public function lookup(){
        try{
            $categories = $this->categoryRepository->all();
            return ApiResponse::success(CategoryLookupResource::collection($categories));
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching categories");
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try{
            $category = $this->categoryRepository->create($request->validated());
            return ApiResponse::success(new CategoryResource($category));
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while creating category");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, String $id)
    {
        try{
            $category = $this->categoryRepository->find($id);
            if(!$category){
                return ApiResponse::error("Category not found");
            }
            return ApiResponse::success(new CategoryResource($category));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while fetching category");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, String $id)
    {
        try{
            $category = $this->categoryRepository->update($id, $request->validated());
            return ApiResponse::success(new CategoryResource($category));
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while updating category", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, String $id)
    {
        try{
            $this->categoryRepository->delete($id);
            return ApiResponse::success([]);
        }
        catch(UserException $e){
            return $e->render($request);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return ApiResponse::error("An error occurred while deleting category", 500);
        }
    }
}
