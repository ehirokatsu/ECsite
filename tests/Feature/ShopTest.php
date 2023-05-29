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

        //$faker = \Faker\Factory::create();
        //$imagePath = $faker->image('storage/app/test', 640, 480);
        //$imageFilename = basename($imagePath);


        //画像を投稿する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.jpg');;
        
        /*
        $data = [
            'name' => $faker->name(),
            'cost' => $faker->randomNumber(4, true),
            'image' => $image
        ];
        */
        $data = [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $image
        ];
        $response = $this->post('/', $data);

        //新規投稿をしたらindexにリダイレクトされること
        $response->assertRedirect('/');
        $response->assertStatus(302);
        $response->assertValid(['name', 'image', 'cost']);

        //生成したテストデータがDBに登録されていること
        $this->assertDatabaseHas('products', [
            'name' => 'testA',
            'cost' => 2000,
        ]);

        //商品名を数値のみにする.name.stringでエラーになること
        $data = [
            'name' => 10,
            'cost' => 2000,
            'image' => $image
        ];
        $response = $this->post('/', $data);
        $response->assertRedirect('/');
        $response->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名をnullにする.name.requiredでエラーになること
        $data = [
            'cost' => 2000,
            'image' => $image
        ];
        $response = $this->post('/', $data);
        $response->assertRedirect('/');
        $response->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);
    }
}
