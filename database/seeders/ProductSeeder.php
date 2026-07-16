<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Category::all()->each(function (Category $category) {
            Product::factory()->count(6)->create(['category_id' => $category->id]);
            Product::factory()->lowStock()->count(2)->create(['category_id' => $category->id]);
        });
    }
}
