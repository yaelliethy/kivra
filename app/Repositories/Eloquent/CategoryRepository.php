<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function filter(Request $request){
        try{
            $query = Category::query();
            if($request->has('name')){
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            if($request->has('description')){
                $query->where('description', 'like', '%'.$request->description.'%');
            }
            if ($request->has('per_page') && $request->per_page > 0 && $request->page_number) {
                return $query->paginate(perPage: $request->per_page, page: $request->page_number);
            }
            return $query->get();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function all(){
        try{
            return Category::select('id', 'name')->get();
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function create(array $data){
        try{
        $category = Category::create([
            'name' => $data['name'],
            'description' => $data['description'],
                'image_url' => $data['image_url'],
            ]);
            return $category;
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function update(int $id, array $data){
        try{
        $category = Category::find($id);
        $category->update([
            'name' => $data['name'] ?? $category->name,
            'description' => $data['description'] ?? $category->description,
            'image_url' => $data['image_url'] ?? $category->image_url,
        ]);
            return $category;
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function delete(int $id){
        try{
            $category = Category::find($id);
            $category->delete();
            return $category;
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
