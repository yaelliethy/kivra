<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

interface ProductRepositoryInterface
{
    public function filter(Request $request);
    public function store(Request $request, User $user);
    public function update(Request $request, Product $product, User $user);
    public function destroy(Product $product, User $user);
}
