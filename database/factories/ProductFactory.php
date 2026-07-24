<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->word(),
            'category_id' => Category::inRandomOrder()->first()->id, // rastgele bir mevcut kategoriye bağla
            'description' => fake()->paragraph(),
            'price'       => rand(1000, 99999), // kuruş cinsinden (10.00 - 999.99 arası)
        ];
    }
}
