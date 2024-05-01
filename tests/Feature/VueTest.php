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

use Carbon\Carbon;
use App\UseCases\Image\CheckFileExists;

class VueTest extends TestCase
{
    //テスト用データベースを初期化する。これがないと以前のテストデータが残る
    use RefreshDatabase;


    protected $checkFileExists;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checkFileExists = $this->app->make(CheckFileExists::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_product_index(): void
    {
        //ログインユーザ無しでアクセスできること
        $response = $this->get(route('vue.index'));
        $response->assertStatus(200);

        //管理者ユーザでアクセスできること
        $adminUser = User::factory()->create();
        $response = $this->actingAs($adminUser)->get(route('vue.index'));
        $response->assertStatus(200);

        //一般ユーザでアクセスできること
        $generalUser = User::factory()->create(['role' => 'general']);
        $response = $this->actingAs($generalUser)->get(route('vue.index'));
        $response->assertStatus(200);
    }

    public function test_product_register(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);
    
        //管理者ユーザで編集画面に遷移する
        $response = $this->actingAs($adminUser)->get(route('vue.create'));

        //遷移できること
        $response->assertStatus(200);

        //管理者ユーザで商品を登録する
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $image
        ]);

        //DBから画像ファイル名を取得する
        $product = Product::where('name', 'testA')->first();
        \Storage::disk('local')->assertExists('/test/' . $product->image);

        //indexにリダイレクトされること
        $response->assertStatus(302)->assertRedirect(route('vue.index'));

        //バリデーションエラーが無いこと
        $response->assertValid(['name', 'imageFileName', 'cost']);

        //生成したテストデータがDBに登録されていること
        $this->assertDatabaseHas('products', [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $product->image
        ]);

        //登録した画像ファイルを削除する
        if (($this->checkFileExists)(storage_path('app/test/') . $product->image)) {
            unlink(storage_path('app/test/') . $product->image); // 画像を削除します
        }

        //登録した画像ファイルが削除されていること
        \Storage::disk('local')->assertMissing('/test/' . $product->image);
        
    }
/*　管理者のみ登録は未実装
    public function test_product_register_ng(): void
    {
        //一般ユーザを作成
        $generalUser = User::factory()->create(['role' => 'general']);

        //画像を生成する
        Storage::fake('test_images');
        $image = UploadedFile::fake()->image('test.jpg');

        //一般ユーザで商品登録する
        $response = $this->actingAs($generalUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 2000,
            'image' => $image
        ]);
        //NGとなること
        $response->assertStatus(403);
    }
*/

    public function test_product_update(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //商品を作成し編集画面を開けること
        $product = Product::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);

        //管理者ユーザで編集画面に遷移する
        $response = $this->actingAs($adminUser)->get(route('vue.edit', ['id' => $product->id]));

        //遷移できること
        $response->assertStatus(200);

        //商品情報を更新できること
        $response = $this->put(route('vue.update', ['id' => $product->id]), [
            'name' => 'testB',
            'cost' => 3000,
            'image' => $image,
        ]);

        //indexにリダイレクトされること
        $response->assertStatus(302);
        
        //DBから画像ファイル名を取得する
        $updateProduct = Product::where('name', 'testB')->first();
        \Storage::disk('local')->assertExists('/test/' . $updateProduct->image);

        //バリデーションエラーが無いこと
        $response->assertValid(['name', 'cost']);

        //更新したテストデータがDBに登録されていること
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'testB',
            'cost' => 3000,
            'image' => $updateProduct->image,
        ]);

        //登録した画像ファイルを削除する
        if (($this->checkFileExists)(storage_path('app/test/') . $updateProduct->image)) {
            unlink(storage_path('app/test/') . $updateProduct->image); // 画像を削除します
        }

        //登録した画像ファイルが削除されていること
        \Storage::disk('local')->assertMissing('/test/' . $product->image);

        //最初に登録した画像ファイルが削除されていること
        \Storage::disk('local')->assertMissing('/test/' . $updateProduct->image);
    }

/*　管理者のみ登録は未実装
    public function test_product_update_ng(): void
    {
        //一般ユーザを作成
        $generalUser = User::factory()->create(['role' => 'general']);

        //商品を作成する
        $product = Product::factory()->create();

        //商品情報を更新できないこと
        $response = $this->actingAs($generalUser)->put(route('vue.update', ['id' => $product->id]), [
            'name' => 'testB',
            'cost' => 3000,
        ]);
        $response->assertStatus(403);

        //factory内のfakerで生成した画像を削除する
        if (($this->checkFileExists)(storage_path('app/test/') . $product->image)) {
            unlink(storage_path('app/test/') . $product->image); // 画像を削除します
        }
    }
*/
    public function test_product_delete(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        $product = Product::factory()->create();

        //dd($product);
        //削除できていること
        $response = $this->actingAs($adminUser)->delete(route('vue.destroy', ['id' => $product->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        
        //factory内のfakerで生成した画像を削除する
        if (($this->checkFileExists)(storage_path('app/test/') . $product->image)) {
            unlink(storage_path('app/test/') . $product->image); // 画像を削除します
        }
    }
    
/*　管理者のみ削除は未実装
    public function test_product_delete_ng(): void
    {
        //一般ユーザを作成
        $generalUser = User::factory()->create(['role' => 'general']);

        $product = Product::factory()->create();
     
        //削除できていること
        $response = $this->actingAs($generalUser)->delete(route('vue.destroy', ['id' => $product->id]));
        $response->assertStatus(403);
        
        //factory内のfakerで生成した画像を削除する
        if (($this->checkFileExists)(storage_path('app/test/') . $product->image)) {
            unlink(storage_path('app/test/') . $product->image); // 画像を削除します
        }
    }
*/
    
}
