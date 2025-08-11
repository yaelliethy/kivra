<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Electronics',
            'description' => 'Electronics category',
            'image_url' => 'https://via.placeholder.com/150',
        ]);
        Category::create([
            'name' => 'Clothing',
            'description' => 'Clothing category',
            'image_url' => 'https://via.placeholder.com/150',
        ]);
        Category::create([
            'name' => 'Books',
            'description' => 'Books category',
            'image_url' => 'https://via.placeholder.com/150',
        ]);
    }
}
