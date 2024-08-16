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

class VueProductValidateTest extends TestCase
{
    //テスト用データベースを初期化する。これがないと以前のテストデータが残る
    use RefreshDatabase;


    public function test_CreateConfirmRequest_name_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);

        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('vue.create'));
    
        //商品名を数値のみにする.name.stringでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 10,
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名をnullにする.name.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名を最大文字数以上にする.name.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => '0123456789012345678901234567890',
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);
    }
    
    public function test_CreateConfirmRequest_cost_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);
    
        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('vue.create'));

        //単価を文字列にする.cost.integerでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 'AAA',
            'image' => $image
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価をnullにする.cost.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'image' => $image
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価を最大値以上にする.cost.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 10001,
            'image' => $image
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価を最小値以下にする.cost.minでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 0,
            'image' => $image
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);
    }

    public function test_CreateConfirmRequest_image_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);

        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('vue.create'));
        
        //画像をnullにする.image.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 2000,
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name', 'cost']);
        $response->assertInvalid(['image']);

        //画像ファイル以外を指定する.image.imageでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 2000,
            'image' => 'test',
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name', 'cost']);
        $response->assertInvalid(['image']);
    }
/* vueでは確認画面からのstore処理は無い
    public function test_StoreRequest_name_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);
    
        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        //$response = $this->actingAs($adminUser)->get(route('vue.create'));

        //商品名を数値のみにする.name.stringでエラーになること
        //まずcreateConfirmにpostしてからstoreする
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'test',
            'cost' => 2000,
            'image' => $image
        ]);
        //保存する画像ファイル名を再取得する
        $imageFileName = session('tmpImageFileName');

        $response = $this->actingAs($adminUser)->post(route('store'), [
            'name' => 10,
            'cost' => 2000,
        ]);
        //リダイレクト先は、最後にgetした/createになってしまう。実機では\createConfirm。
        //本質ではないためAssertRedirectはindexとして記述する
        //$response->assertRedirect(route('vue.store'))->assertStatus(302);
        $response->assertRedirect(route('index'))->assertStatus(302);
        $response->assertValid(['cost']);
        $response->assertInvalid(['name']);

        //商品名をnullにする.name.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'cost' => 2000,
        ]);
        $response->assertRedirect(route('index'))->assertStatus(302);
        $response->assertValid(['cost']);
        $response->assertInvalid(['name']);

        //商品名を最大文字数以上にする.name.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => '0123456789012345678901234567890',
            'cost' => 2000,
        ]);
        $response->assertRedirect(route('index'))->assertStatus(302);
        $response->assertValid(['cost']);
        $response->assertInvalid(['name']);

        //登録した画像ファイルを削除する
        //createConfirmのみなのでtmpフォルダ内を削除する
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder')) . $imageFileName)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder')) . $imageFileName); // 画像を削除します
        }
    }

    public function test_StoreRequest_cost_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);

        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('vue.create'));

        //単価を文字列にする.cost.integerでエラーになること
        //まずcreateConfirmにpostしてからstoreする
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'test',
            'cost' => 2000,
            'image' => $image
        ]);
        //保存する画像ファイル名を再取得する
        $imageFileName = session('tmpImageFileName');

        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'test',
            'cost' => 'AAA',
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name']);
        $response->assertInvalid(['cost']);

        //単価を最大値以上にする.cost.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 10001,
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name']);
        $response->assertInvalid(['cost']);

        //単価を最小値以下にする.cost.minでエラーになること
        $response = $this->actingAs($adminUser)->post(route('vue.store'), [
            'name' => 'testA',
            'cost' => 0,
        ]);
        $response->assertRedirect(route('vue.create'))->assertStatus(302);
        $response->assertValid(['name']);
        $response->assertInvalid(['cost']);

        //登録した画像ファイルを削除する
        //createConfirmのみなのでtmpフォルダ内を削除する
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder')) . $imageFileName)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder')) . $imageFileName); // 画像を削除します
        }
    }
*/
    public function test_EditConfirmRequest_name_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //商品を作成
        $product = Product::factory()->create();

        
        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('vue.edit', ['id' => $product->id]));
    
        //dump($response);
        //dump($product->id);
        //商品名を数値のみにする.name.stringでエラーになること
        $response = $this->actingAs($adminUser)->put(route('vue.update', ['id' => $product->id]), [
            'name' => 10,
        ]);

        $response->assertRedirect(route('vue.edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['name']);
        //dump($response);

        //商品名を最大文字数以上にする.name.maxでエラーになること
        $response = $this->actingAs($adminUser)->put(route('vue.update', ['id' => $product->id]), [
            'name' => '0123456789012345678901234567890',
        ]);
        $response->assertRedirect(route('vue.edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['name']);

        //商品画像を削除する
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image); // 画像を削除します
        }
    }

    public function test_EditConfirmRequest_cost_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //商品を作成
        $product = Product::factory()->create();

        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('vue.edit', ['id' => $product->id]));

        //単価を文字列にする.cost.integerでエラーになること
        $response = $this->actingAs($adminUser)->put(route('vue.update', ['id' => $product->id]), [
            'cost' => 'AAA',
        ]);
        $response->assertRedirect(route('vue.edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['cost']);

        //単価を最大値以上にする.cost.maxでエラーになること
        $response = $this->actingAs($adminUser)->put(route('vue.update', ['id' => $product->id]), [
            'cost' => 10001,
        ]);
        $response->assertRedirect(route('vue.edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['cost']);

        //単価を最小値以下にする.cost.minでエラーになること
        $response = $this->actingAs($adminUser)->put(route('vue.update', ['id' => $product->id]), [
            'cost' => 0,
        ]);
        $response->assertRedirect(route('vue.edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['cost']);

        //商品画像を削除する
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image); // 画像を削除します
        }
    }

    public function test_EditConfirmRequest_image_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //商品を作成
        $product = Product::factory()->create();

        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('vue.edit', ['id' => $product->id]));

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);

        //画像ファイル以外を指定する.image.imageでエラーになること
        $response = $this->actingAs($adminUser)->put(route('vue.update', ['id' => $product->id]), [
            'name' => 'testA',
            'cost' => 2000,
            'image' => 'test',
        ]);
        $response->assertRedirect(route('vue.edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertValid(['name', 'cost']);

        //商品画像を削除する
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image); // 画像を削除します
        }
            
    }
/*
    public function test_UpdateRequest_name_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //商品を作成
        $product = Product::factory()->create();

        //フォームリクエスト失敗時のリダイレクト先を実機と同じくeditにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('edit', ['id' => $product->id]));

        //商品名を数値のみにする.name.stringでエラーになること
        //$response = $this->actingAs($adminUser)->post(route('editConfirm', ['id' => $product->id]), [
        //   'name' => 'AAA',
            //'cost' => 1000,
        //]);

        //商品名を数値のみにする.name.stringでエラーになること
        $response = $this->actingAs($adminUser)->put(route('update', ['id' => $product->id]), [
            'name' => 10,
            'cost' => 1000,
        ]);
        $response->assertRedirect(route('edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['name']);


        //商品名を最大文字数以上にする.name.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('editConfirm', ['id' => $product->id]), [
            'name' => '0123456789012345678901234567890',
        ]);
        $response->assertRedirect(route('edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['name']);

        //商品画像を削除する
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image); // 画像を削除します
        }
    }

    public function test_UpdateRequest_cost_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //商品を作成
        $product = Product::factory()->create();

        //フォームリクエスト失敗時のリダイレクト先を実機と同じくeditにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('edit', ['id' => $product->id]));

        //単価を文字列にする.cost.integerでエラーになること
        $response = $this->actingAs($adminUser)->put(route('update', ['id' => $product->id]), [
            'cost' => 'AAA',
        ]);
        $response->assertRedirect(route('edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['cost']);

        //単価を最大値以上にする.cost.maxでエラーになること
        $response = $this->actingAs($adminUser)->put(route('update', ['id' => $product->id]), [
            'cost' => 10001,
        ]);
        $response->assertRedirect(route('edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['cost']);

        //単価を最小値以下にする.cost.minでエラーになること
        $response = $this->actingAs($adminUser)->put(route('update', ['id' => $product->id]), [
            'cost' => 0,
        ]);
        $response->assertRedirect(route('edit', ['id' => $product->id]))->assertStatus(302);
        $response->assertInvalid(['cost']);

        //商品画像を削除する
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image); // 画像を削除します
        }
    }
*/

    public function checkFileExists($path) {
        if (\File::exists($path) && !is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }
    
}
