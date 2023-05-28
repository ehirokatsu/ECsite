<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//factoryで使用
use App\Models\Product;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class ShopTest extends TestCase
{
    //テスト用データベースを初期化する。これがないと以前のテストデータが残る
    //use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);


        //$product = Product::factory()->create();

        $faker = \Faker\Factory::create();
        //$imagePath = $faker->image('storage/app/test', 640, 480);
        //$imageFilename = basename($imagePath);


        //画像を投稿する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.jpg');

        
        $data = [
            //
            'name' => $faker->name(),
            'cost' => strval($faker->randomNumber(4, true)),
            'image' => $image
        ];
        $response = $this->post('/', $data);
        //新規投稿をしたらindexにリダイレクトされる
        $response->assertRedirect('/');
        //$response->assertStatus(302);
        $response->assertValid(['image']);
        $response->assertValid(['cost']);
    }
}
