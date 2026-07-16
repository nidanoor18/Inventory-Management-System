<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####??')),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 500),
            'quantity' => fake()->numberBetween(0, 200),
            'reorder_level' => fake()->numberBetween(5, 30),
        ];
    }

    public function lowStock(): static
    {
        return $this->state(function (array $attributes) {
            $reorderLevel = $attributes['reorder_level'] ?? 10;

            return [
                'reorder_level' => $reorderLevel,
                'quantity' => fake()->numberBetween(0, $reorderLevel),
            ];
        });
    }
}
