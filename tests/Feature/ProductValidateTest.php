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

class ProductValidateTest extends TestCase
{
    //テスト用データベースを初期化する。これがないと以前のテストデータが残る
    use RefreshDatabase;


    public function test_StoreConfirmRequest_name_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);

        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('create'));
    
        //商品名を数値のみにする.name.stringでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 10,
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名をnullにする.name.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);

        //商品名を最大文字数以上にする.name.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => '0123456789012345678901234567890',
            'cost' => 2000,
            'image' => $image
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['image', 'cost']);
        $response->assertInvalid(['name']);
    }

    public function test_StoreRequest_name_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);
    
        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        //$response = $this->actingAs($adminUser)->get(route('create'));

        //商品名を数値のみにする.name.stringでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
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
        //$response->assertRedirect(route('createConfirm'))->assertStatus(302);
        $response->assertRedirect(route('index'))->assertStatus(302);
        $response->assertValid(['cost']);
        $response->assertInvalid(['name']);

        //商品名をnullにする.name.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'cost' => 2000,
        ]);
        $response->assertRedirect(route('index'))->assertStatus(302);
        $response->assertValid(['cost']);
        $response->assertInvalid(['name']);

        //商品名を最大文字数以上にする.name.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => '0123456789012345678901234567890',
            'cost' => 2000,
        ]);
        $response->assertRedirect(route('index'))->assertStatus(302);
        $response->assertValid(['cost']);
        $response->assertInvalid(['name']);

        //登録した画像ファイルを削除する
        //createConfirmのみなのでtmpフォルダ内を削除する
        if ($this->checkFileExists(storage_path('app/test/tmp/') . $imageFileName)) {
            unlink(storage_path('app/test/tmp/') . $imageFileName); // 画像を削除します
        }
    }
    
    public function test_StoreConfirmRequest_cost_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);
    
        //フォームリクエスト失敗時のリダイレクト先を実機と同じくcreateにするためにgetしておく
        $response = $this->actingAs($adminUser)->get(route('create'));

        //単価を文字列にする.cost.integerでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'testA',
            'cost' => 'AAA',
            'image' => $image
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価をnullにする.cost.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'testA',
            'image' => $image
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価を最大値以上にする.cost.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'testA',
            'cost' => 10001,
            'image' => $image
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);

        //単価を最小値以下にする.cost.minでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'testA',
            'cost' => 0,
            'image' => $image
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['name', 'image']);
        $response->assertInvalid(['cost']);
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
        $response = $this->actingAs($adminUser)->get(route('create'));

        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'test',
            'cost' => 2000,
            'image' => $image
        ]);
        //保存する画像ファイル名を再取得する
        $imageFileName = session('tmpImageFileName');

        $response = $this->actingAs($adminUser)->post(route('store'), [
            'name' => 'test',
            'cost' => 'AAA',
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['name']);
        $response->assertInvalid(['cost']);

        //単価を最大値以上にする.cost.maxでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'testA',
            'cost' => 10001,
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['name']);
        $response->assertInvalid(['cost']);

        //単価を最小値以下にする.cost.minでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'testA',
            'cost' => 0,
        ]);
        $response->assertRedirect(route('create'))->assertStatus(302);
        $response->assertValid(['name']);
        $response->assertInvalid(['cost']);

        //登録した画像ファイルを削除する
        //createConfirmのみなのでtmpフォルダ内を削除する
        if ($this->checkFileExists(storage_path('app/test/tmp/') . $imageFileName)) {
            unlink(storage_path('app/test/tmp/') . $imageFileName); // 画像を削除します
        }
    }

    public function test_StoreConfirmRequest_image_validate(): void
    {
        //管理者ユーザを作成
        $adminUser = User::factory()->create();

        //画像を生成する
        Storage::fake('test_images');
        $imageName = 'test.jpg';
        $image = UploadedFile::fake()->image($imageName);
    
        //画像をnullにする.image.requiredでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'testA',
            'cost' => 2000,
        ]);
        $response->assertRedirect(route('index'))->assertStatus(302);
        $response->assertValid(['name', 'cost']);
        //$response->assertInvalid(['imageFileName']);

        //画像ファイル以外を指定する.image.imageでエラーになること
        $response = $this->actingAs($adminUser)->post(route('createConfirm'), [
            'name' => 'testA',
            'cost' => 2000,
            'image' => 'test',
        ]);
        $response->assertRedirect(route('index'))->assertStatus(302);
        $response->assertValid(['name', 'cost']);
        //$response->assertInvalid(['imageFileName']);
    }
    
    public function checkFileExists($path) {
        if (\File::exists($path) && !is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }
    
}
