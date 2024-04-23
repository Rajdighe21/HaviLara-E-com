<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        $title = fake()->unique()->name();
        $slug = Str::slug($title);
        $sabCategories = [4, 5];
        $brands = [2, 10, 5, 11, 12];

        $sabCatRandKey = array_rand($sabCategories);
        $brandRandKey = array_rand($brands);

        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => 3,
            'sub_category_id' => $sabCategories[$sabCatRandKey],
            'brand_id' => $brands[$brandRandKey],
            'price' => rand(10, 10000),
            'sku' => rand(1000, 200000),
            'track_qty' => 'Yes',
            'qty' => 10,
            'is_featured' => 'Yes',
            'status' => 1,
        ];
    }
}
