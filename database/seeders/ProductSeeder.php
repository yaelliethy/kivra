<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::first()->id;
        $categoryId = Category::first()->id;
        Product::create([
            'name' => 'Product 1',
            'description' => 'Description 1',
            'price' => 100,
            'image_url' => 'https://via.placeholder.com/150',
            'category_id' => $categoryId,
            'stock' => 100,
            'user_id' => $userId,
        ]);
        Product::create([
            'name' => 'Product 2',
            'description' => 'Description 2',
            'price' => 200,
            'image_url' => 'https://via.placeholder.com/150',
            'category_id' => $categoryId,
            'stock' => 200,
            'user_id' => $userId,
        ]);
        Product::create([
            'name' => 'Product 3',
            'description' => 'Description 3',
            'price' => 300,
            'image_url' => 'https://via.placeholder.com/150',
            'category_id' => $categoryId,
            'stock' => 300,
            'user_id' => $userId,
        ]);
    }
}
