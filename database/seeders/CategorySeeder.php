<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        collect(['Electronics', 'Office Supplies', 'Furniture', 'Packaging', 'Tools'])
            ->each(fn (string $name) => Category::factory()->create(['name' => $name]));
    }
}
