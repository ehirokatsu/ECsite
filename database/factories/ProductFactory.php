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
        //画像を生成する
        $oldPath = $this->faker->image(storage_path('app/test'), 640, 480);

        //ファイル名を作成する
        $newPath = storage_path('app/test/') . date('Y-m-d_H-i-s') . '_test_' . \Str::random(5) . '.jpg';

        //fakerだとファイル名を指定できないのでrenameをする
        rename($oldPath, $newPath);
        //\Log::info($imagePath);
        $imageFilename = basename($newPath);

        return [
            //
            'name' => $this->faker->name(),
            'cost' => $this->faker->randomNumber(4, true),
            'image' => $imageFilename,
        ];
    }
}
