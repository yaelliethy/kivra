<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

interface CategoryRepositoryInterface
{
    public function all();
    public function filter(Request $request);
    public function create(array $data);
    public function update(int $id, array $data);
}
