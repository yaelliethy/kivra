<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\User;
use App\Exceptions\UserException;
class ProductRepository implements ProductRepositoryInterface
{
    public function filter(array $request)
    {
        $query = Product::query();
        $query->select('id', 'name', 'price', 'image_url', 'category_id', 'user_id', 'stock', 'description', 'created_at', 'updated_at');
        if (isset($request['category_id'])) {
            $query->where('category_id', $request['category_id']);
        }
        if (isset($request['name'])) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }
        if (isset($request['seller_id'])) {
            $query->where('user_id', $request['seller_id']);
        }
        if (isset($request['price_min'])) {
            $query->where('price', '>=', $request['price_min']);
        }
        if (isset($request['price_max'])) {
            $query->where('price', '<=', $request['price_max']);
        }
        if (isset($request['sort_by']) && isset($request['sort_order'])) {
            $allowedSortBy = ['price', 'name', 'created_at'];
            $allowedSortOrder = ['asc', 'desc'];
            if (in_array($request['sort_by'], $allowedSortBy) && in_array($request['sort_order'], $allowedSortOrder)) {
                $query->orderBy($request['sort_by'], $request['sort_order']);
            } else {
                throw new UserException('Invalid sort by or sort order');
            }
        }
        if (isset($request['per_page']) && isset($request['page_number'])) {
            return $query->paginate(perPage: $request['per_page'], page: $request['page_number']);
        }
        return $query->get();
    }

    public function find(string $id)
    {
        $product = Product::with('user')->find($id);
        if (!$product) {
            throw new UserException('Product not found');
        }
        return $product;
    }
    public function store(array $request, User $user)
    {
        $product = Product::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'image_url' => $request['image_url'],
            'category_id' => $request['category_id'],
            'user_id' => $user->id,
            'stock' => $request['stock'],
        ]);
        return $product;
    }

    public function update(array $request, string $id, User $user)
    {
        $product = Product::find($id);
        if (!$product) {
            throw new UserException('Product not found');
        }
        if ($product->user_id !== $user->id && !$user->is_admin) {
            throw new UserException('You are not authorized to update this product');
        }
        $product->update([
            'name' => $request['name'] ?? $product->name,
            'description' => $request['description'] ?? $product->description,
            'price' => $request['price'] ?? $product->price,
            'image_url' => $request['image_url'] ?? $product->image_url,
            'category_id' => $request['category_id'] ?? $product->category_id,
            'stock' => $request['stock'] ?? $product->stock,
        ]);
        return $product;
    }

    public function destroy(string $id, User $user)
    {
        $product = Product::find($id);
        if (!$product) {
            throw new UserException('Product not found');
        }
        if ($product->user_id !== $user->id && !$user->is_admin) {
            throw new UserException('You are not authorized to delete this product');
        }
        $product->delete();
        return $product;
    }
}
