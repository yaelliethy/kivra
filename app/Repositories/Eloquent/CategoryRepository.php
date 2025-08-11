<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function filter(Request $request){
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

    public function all(){
        return Category::select('id', 'name')->get();
    }
}
