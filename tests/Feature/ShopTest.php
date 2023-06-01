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
    public function test_product_register(): void
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
        
        $response = $this->post('/', [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $image
        ]);

        //新規投稿をしたらindexにリダイレクトされること
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'image', 'cost']);

        //生成したテストデータがDBに登録されていること
        $this->assertDatabaseHas('products', [
            'name' => 'testA',
            'cost' => 2000,
        ]);

    }

    public function test_product_update(): void
    {
        //商品を作成し編集画面を開けること
        $product = Product::factory()->create();
        $response = $this->get('/' . $product->id . '/edit');
        $response->assertStatus(200);

        //商品情報を更新できること
        $response = $this->put('/' . $product->id, [
            'name' => 'testB',
            'cost' => 3000,
        ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'testB',
            'cost' => 3000,
        ]);

    }

    public function test_product_delete(): void
    {

        $product = Product::factory()->create();
        
        //削除できていること
        $response = $this->delete('/' . $product->id);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        
    }


    public function test_name_validate(): void
    {
        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.jpg');;

        //商品名を数値のみにする.name.stringでエラーになること
        $response = $this->post('/', [
            'name' => 10,
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名をnullにする.name.requiredでエラーになること
        $response = $this->post('/', [
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名を最大文字数以上にする.name.maxでエラーになること
        $response = $this->post('/', [
            'name' => '0123456789012345678901234567890',
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

    }


    public function test_cost_validate(): void
    {
        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.jpg');;

        //単価を文字列にする.cost.integerでエラーになること
        $response = $this->post('/', [
            'name' => 'testA',
            'cost' => 'AAA',
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価をnullにする.cost.requiredでエラーになること
        $response = $this->post('/', [
            'name' => 'testA',
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価を最大値以上にする.cost.maxでエラーになること
        $response = $this->post('/', [
            'name' => 'testA',
            'cost' => 10001,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価を最小値以下にする.cost.minでエラーになること
        $response = $this->post('/', [
            'name' => 'testA',
            'cost' => 0,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);
    }

    public function test_image_validate(): void
    {
        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.txt');;

        //画像をnullにする.image.requiredでエラーになること
        $response = $this->post('/', [
            'name' => 'testA',
            'cost' => 2000,
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'cost']);
        $response->assertInvalid(['image']);

        //画像ファイル以外を指定する.image.imageでエラーになること
        $response = $this->post('/', [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'cost']);
        $response->assertInvalid(['image']);

    }
}
