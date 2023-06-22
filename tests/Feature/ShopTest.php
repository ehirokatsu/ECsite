<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//factoryで使用
use App\Models\Product;
use App\Models\User;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class ShopTest extends TestCase
{
    //テスト用データベースを初期化する。これがないと以前のテストデータが残る
    //use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_product_index(): void
    {
        //ログインユーザ無しでアクセスできること
        $response = $this->get(route('index'));
        $response->assertStatus(200)->assertViewIs('index');

        //管理者ユーザでアクセスできること
        $adminUser = User::factory()->create();
        $response = $this->actingAs($adminUser)->get(route('index'));
        $response->assertStatus(200)->assertViewIs('index');

        //一般ユーザでアクセスできること
        $generalUser = User::factory()->create(['role' => 'general']);
        $response = $this->actingAs($generalUser)->get(route('index'));
        $response->assertStatus(200)->assertViewIs('index');
    }

    public function test_product_register(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //$product = Product::factory()->create();
        //$faker = \Faker\Factory::create();
        //$imagePath = $faker->image('storage/app/test', 640, 480);
        //$imageFilename = basename($imagePath);

        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.jpg');;
        //\Log::info('image=' . $image);

        //管理者ユーザで商品を登録する
        $response = $this->actingAs($adminUser)->post(route('store'), [
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

    public function test_product_register_ng(): void
    {
        //一般ユーザを作成
        $generalUser = User::factory()->create(['role' => 'general']);

        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.jpg');;

        //一般ユーザで商品登録する
        $response = $this->actingAs($generalUser)->post(route('store'), [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $image
        ]);
        //NGとなること
        $response->assertStatus(403);
    }

    public function test_product_update(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //商品を作成し編集画面を開けること
        $product = Product::factory()->create();

        $response = $this->actingAs($adminUser)->get(route('edit', ['id' => $product->id]));
        $response->assertStatus(200);

        //商品情報を更新できること
        $response = $this->put(route('update', ['id' => $product->id]), [
            'name' => 'testB',
            'cost' => 3000,
        ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'testB',
            'cost' => 3000,
        ]);

        //factory内のfakerで生成した画像を削除する
        //\Log::info('imagePath=' . storage_path('app/public/fake/') . $product->image);
        if (file_exists(storage_path('app/public/fake/') . $product->image)) {
            unlink(storage_path('app/public/fake/') . $product->image); // 画像を削除します
        }
    }

    public function test_product_update_ng(): void
    {
        //一般ユーザを作成
        $generalUser = User::factory()->create(['role' => 'general']);

        //商品を作成し編集画面を開けること
        $product = Product::factory()->create();

        $response = $this->actingAs($generalUser)->get(route('edit', ['id' => $product->id]));
        $response->assertStatus(403);

        //商品情報を更新できること
        $response = $this->actingAs($generalUser)->put(route('update', ['id' => $product->id]), [
            'name' => 'testB',
            'cost' => 3000,
        ]);
        $response->assertStatus(403);

        //factory内のfakerで生成した画像を削除する
        if (file_exists(storage_path('app/public/fake/') . $product->image)) {
            unlink(storage_path('app/public/fake/') . $product->image); // 画像を削除します
        }
    }

    public function test_product_delete(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        $product = Product::factory()->create();
        
        //削除できていること
        $response = $this->actingAs($adminUser)->delete(route('destroy', ['id' => $product->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        
        //factory内のfakerで生成した画像を削除する
        if (file_exists(storage_path('app/public/fake/') . $product->image)) {
            unlink(storage_path('app/public/fake/') . $product->image); // 画像を削除します
        }
    }

    public function test_product_delete_ng(): void
    {
        //一般ユーザを作成
        $generalUser = User::factory()->create(['role' => 'general']);

        $product = Product::factory()->create();
        
        //削除できていること
        $response = $this->actingAs($generalUser)->delete(route('destroy', ['id' => $product->id]));
        $response->assertStatus(403);
        
        //factory内のfakerで生成した画像を削除する
        if (file_exists(storage_path('app/public/fake/') . $product->image)) {
            unlink(storage_path('app/public/fake/') . $product->image); // 画像を削除します
        }
    }

    public function test_name_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.jpg');

        //商品名を数値のみにする.name.stringでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
            'name' => 10,
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名をnullにする.name.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名を最大文字数以上にする.name.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
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
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.jpg');

        //単価を文字列にする.cost.integerでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
            'name' => 'testA',
            'cost' => 'AAA',
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価をnullにする.cost.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
            'name' => 'testA',
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価を最大値以上にする.cost.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
            'name' => 'testA',
            'cost' => 10001,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価を最小値以下にする.cost.minでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
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
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('post.txt');

        //画像をnullにする.image.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
            'name' => 'testA',
            'cost' => 2000,
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'cost']);
        $response->assertInvalid(['image']);

        //画像ファイル以外を指定する.image.imageでエラーになること
        $response = $this->actingAs($adminUser)->post(route('store'), [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['name', 'cost']);
        $response->assertInvalid(['image']);
    }
}
