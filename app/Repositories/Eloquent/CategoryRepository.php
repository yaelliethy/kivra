<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use App\Exceptions\UserException;
use Exception;
class CategoryRepository implements CategoryRepositoryInterface
{
    public function filter(array $request)
    {
        $query = Category::query();
        if (isset($request['name'])) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }
        if (isset($request['description'])) {
            $query->where('description', 'like', '%' . $request['description'] . '%');
        }
        if (isset($request['per_page']) && isset($request['page_number'])) {
            return $query->paginate(perPage: $request['per_page'], page: $request['page_number']);
        }
        return $query->get();
    }

    public function all()
    {
        return Category::select('id', 'name')->get();
    }

    public function create(array $data)
    {
        $category = Category::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'image_url' => $data['image_url'],
        ]);
        return $category;
    }

    public function find(string $id)
    {
        $category = Category::find($id);
        return $category;
    }

    public function update(string $id, array $data)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new UserException("Category not found");
        }
        $category->update([
            'name' => $data['name'] ?? $category->name,
            'description' => $data['description'] ?? $category->description,
            'image_url' => $data['image_url'] ?? $category->image_url,
        ]);
        return $category;
    }

    public function delete(string $id)
    {
        $category = Category::find($id);
        $category->delete();
        return $category;
    }
}
