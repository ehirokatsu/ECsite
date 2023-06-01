<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;

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

        $imagePath = $this->faker->image('public/storage', 640, 480);
        $imageFilename = basename($imagePath);

        return [
            //
            'name' => $this->faker->name(),
            'cost' => $this->faker->randomNumber(4, true),
            'image' => $imageFilename,
        ];
    }
}
