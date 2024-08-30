<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        //$oldPath = $this->faker->image(storage_path('app/test'), 640, 480);

        //Windowsだとfaker->imageに失敗することがあるので下記方法にする
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        //C:\~\AppData\Local\Temp\php〇〇.tmpに保存される
        $oldPath = UploadedFile::fake()->image($imageName);

        //保存場所、ファイル名を変更する
        $newPath = storage_path('app/test/') . Carbon::now()->format('Y_m_d_H_i_s') . '_test_' . \Str::random(5) . '.jpg';
        rename($oldPath, $newPath);

        //\Log::info($imagePath);
        $imageFilename = basename($newPath);

        return [
            //
            'name' => Str::limit($this->faker->name(), 30),  // 名前を30文字以内に制限
            'cost' => $this->faker->randomNumber(4, true),
            'image' => $imageFilename,
        ];
    }
}
