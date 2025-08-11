<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class ProductRepository implements ProductRepositoryInterface
{
    public function filter(Request $request)
    {
        $query = Product::query();
        $query->select('id', 'name', 'price', 'image_url', 'category_id', 'user_id');
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('seller_id')) {
            $query->where('user_id', $request->seller_id);
        }
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $allowedSortBy = ['price', 'name', 'created_at'];
            $allowedSortOrder = ['asc', 'desc'];
            if (in_array($request->sort_by, $allowedSortBy) && in_array($request->sort_order, $allowedSortOrder)) {
                $query->orderBy($request->sort_by, $request->sort_order);
            }
            else {
                throw new \Exception('Invalid sort by or sort order');
            }
        }
        if ($request->has('per_page') && $request->per_page > 0 && $request->page_number) {
            return $query->paginate(perPage: $request->per_page, page: $request->page_number);
        }
        return $query->get();
    }

    public function store(Request $request, User $user)
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $request->image_url,
            'category_id' => $request->category_id,
            'user_id' => $user->id,
        ]);
        return $product;
    }

    public function update(Request $request, Product $product, User $user)
    {
        if ($product->user_id !== $user->id && !$user->is_admin) {
            throw new \Exception('You are not authorized to update this product');
        }
        $product->update([
            'name' => $request->name ?? $product->name,
            'description' => $request->description ?? $product->description,
            'price' => $request->price ?? $product->price,
            'image_url' => $request->image_url ?? $product->image_url,
            'category_id' => $request->category_id ?? $product->category_id,
        ]);
        return $product;
    }

    public function destroy(Product $product, User $user)
    {
        if ($product->user_id !== $user->id && !$user->is_admin) {
            throw new \Exception('You are not authorized to delete this product');
        }
        $product->delete();
        return $product;
    }
}
