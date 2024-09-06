<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//factoryで使用
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class VueCartTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    //ログイン無しでカートに商品を追加できること
    public function test_cart_store_and_delete(): void
    {
        //商品を追加
        $product = Product::factory()->create();

        //カートに追加
        $response = $this->post(route('vue.cart.store'), [
            'id' => $product->id,
        ]);
        //\Log::info($response);
        //$response->dumpSession();

        //カート一覧画面にリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('vue.cart.index'));
        
        //sessionに保存されていること
        $response->assertSessionHas('carts', function ($value) use ($product) {
            return $value[0]['product']->id === $product->id;
        });

        //別の商品を追加
        $product2 = Product::factory()->create();

        //カートに追加
        $response = $this->post(route('vue.cart.store'), [
            'id' => $product2->id,
        ]);

        //カートから最初の商品を削除
        $response = $this->delete(route('vue.cart.destroy', ['id' => $product->id]));
        //$response = $this->delete('/cart/' . $product->id);
        //$response->dumpSession();

        //最初の商品がカートから削除されていること
        $response->assertSessionHas('carts', function ($value) use ($product) {
            return $value[0]['product']->id !== $product->id;
        });    
        
        //商品追加で生成した画像を削除
        //dd(storage_path('app/test/') . $product->image);
        //dd($this->checkFileExists(storage_path('app/test/') . $product->image));
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image); // 画像を削除します
        }
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product2->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product2->image); // 画像を削除します
        }
    }
/*
    //同じ商品をカートに追加すると別画面が表示されカートに入っていないこと
    public function test_same_product_cart(): void
    {
        //商品を追加
        $product = Product::factory()->create();

        //カートに追加
        $response = $this->post(route('cart.store'), [
            'id' => $product->id,
        ]);

        //カート一覧画面にリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('cart.index'));

        //sessionに保存されていること
        $response->assertSessionHas('carts', function ($value) use ($product) {
            return $value[0]['product']->id === $product->id;
        });

        //カートに追加
        $response = $this->post(route('cart.store'), [
            'id' => $product->id,
        ]);

        //エラーページが表示されること
        $response->assertStatus(200)->assertViewIs('cart.duplication');

        //商品追加で生成した画像を削除
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image);
        }
    }
*/
    //カートの商品の個数を変更できること
    public function test_change_quantity(): void
    {
        //商品を追加
        $product = Product::factory()->create();

        //カートに追加
        $response = $this->post(route('vue.cart.store'), [
            'id' => $product->id,
        ]);

        //カート一覧画面にリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('vue.cart.index'));
        
        //個数を変更する
        $updateQuantity = 2;
        $response = $this->put(route('vue.cart.quantityUpdate', ['id' => $product->id]), [
            'quantity' => $updateQuantity,
        ]);

        //$response->dumpSession();
        //sessionに保存されていること
        $response->assertSessionHas('carts', function ($value) use ($updateQuantity) {
            return $value[0]['quantity'] === $updateQuantity;
        });

        //商品追加で生成した画像を削除
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image);
        }
    }
/*
    //購入する時にログインして購入できること
    public function test_login_can_buy(): void
    {
        //商品登録
        $product = Product::factory()->create();

        //カートに商品を追加
        $response = $this->post(route('cart.store'), [
            'id' => $product->id
        ]);

        //カート一覧画面にリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('cart.index'));
        
        //購入ボタンを押下
        $response = $this->get(route('cart.regConfirm'));

        //ログイン画面へリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('login'));

        //ユーザー生成
        $user = User::factory()->create();

        //ログインする
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $user->password,
        ]);

        //購入確認画面へリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('cart.regConfirm'));

        //購入完了確定ボタンを押下
        $response = $this->actingAs($user)->post(route('cart.regComplete'), [
            'name' => $user->name,
            'email' => $user->email,
            'postalCode' => $user->postal_code,
            'address1' => $user->address_1,
            'address2' => $user->address_2,
            'address3' => $user->address_3,
            'phoneNumber' => $user->phone_number,
            'action' => 'submit',
        ]);

        //購入完了画面へ遷移すること
        $response->assertStatus(200)->assertViewIs('cart.regComplete');

        //カートの中身がクリアされていること
        $response->assertSessionMissing('carts');

        //商品追加で生成した画像を削除
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image);
        }
    }

    //購入する時に登録して購入できること
    public function test_register_can_buy(): void
    {
        //商品登録
        $product = Product::factory()->create();

        //カートに商品を追加
        $response = $this->post(route('cart.store'), [
            'id' => $product->id,
        ]);

        //カート一覧画面にリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('cart.index'));

        //購入ボタンを押下
        $response = $this->get(route('cart.regConfirm'));

        //ログイン画面へリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('login'));

        //登録ボタンを押下する
        $response = $this->post(route('cart.register'));

        //登録するユーザーデータ
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'testtest', // password
            'password_confirmation' => 'testtest', // password
            'postalCode' => '1001000',
            'address1' => '東京都',
            'address2' => '中央区',
            'address3' => 'テスト市',
            'phoneNumber' => '09012345678',
        ];

        //ユーザーデータを登録
        $response = $this->post(route('register'), $userData);

        //登録後、購入者確認画面へリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('cart.regConfirm'));

        //購入完了確定ボタンを押下
        $response = $this->post(route('cart.regComplete'), $userData);

        //購入完了画面へ遷移すること
        $response->assertStatus(200)->assertViewIs('cart.regComplete');

        //カートの中身がクリアされていること
        $response->assertSessionMissing('carts');

        //商品追加で生成した画像を削除
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image);
        }
    }

    //最初からログインした状態でカート入力、購入確認画面、購入できること
    public function test_logined_can_buy(): void
    {
        //ユーザ作成
        $user = User::factory()->create();

        //商品登録
        $product = Product::factory()->create();

        //カートに商品を追加
        $response = $this->actingAs($user)->post(route('cart.store'), [
            'id' => $product->id,
        ]);

        //カート一覧画面にリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('cart.index'));

        //購入ボタンを押下
        $response = $this->actingAs($user)->get(route('cart.regConfirm'));

        //購入者確認画面に遷移すること
        $response->assertStatus(200)->assertViewIs('cart.regConfirm');

        //購入完了確定ボタンを押下
        $response = $this->actingAs($user)->post(route('cart.regComplete'), [
            'name' => $user->name,
            'email' => $user->email,
            'postalCode' => $user->postal_code,
            'address1' => $user->address_1,
            'address2' => $user->address_2,
            'address3' => $user->address_3,
            'phoneNumber' => $user->phone_number,
            'action' => 'submit',
        ]);

        //購入完了画面へ遷移すること
        $response->assertStatus(200)->assertViewIs('cart.regComplete');

        //カートの中身がクリアされていること
        $response->assertSessionMissing('carts');

        //商品追加で生成した画像を削除
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image);
        }
    }

    //登録しないで購入できること
    public function no_register_can_buy(): void
    {
        //商品登録
        $product = Product::factory()->create();

        //カートに商品を追加
        $response = $this->post(route('cart.store'), [
            'id' => $product->id,
        ]);
        
        //カート一覧画面にリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('cart.index'));

        //購入ボタンを押下
        $response = $this->get(route('cart.regConfirm'));

        //ログイン画面へリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('login'));

        //登録せずに購入するボタン押下
        $response = $this->get(route('cart.buyer'));

        //購入者情報入力画面に遷移すること
        $response->assertStatus(200)->assertViewIs('cart.buyer');

        //購入者データ
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'postalCode' => '1001000',
            'address1' => '東京都',
            'address2' => '中央区',
            'address3' => 'テスト市',
            'phoneNumber' => '09012345678',
        ];

        //購入者データを入力する
        $response = $this->post(route('cart.buyerConfirm'), $userData);

        //購入者確認画面に遷移すること
        $response->assertStatus(200)->assertViewIs('cart.buyerConfirm');

        //修正ボタンを押下する
        $response = $this->post(route('cart.buyerComplete'), array_push($userData, ['action' => 'back']));

        //購入者情報入力画面にリダイレクトすること
        $response->assertStatus(302)->assertViewIs('cart.buyer');

        //購入ボタンを押下する
        $response = $this->post(route('cart.buyerComplete'), array_push($userData, ['action' => 'submit']));

        //購入完了画面へ遷移すること
        $response->assertStatus(200)->assertViewIs('cart.Complete');

        //カートの中身がクリアされていること
        $response->assertSessionMissing('carts');

        //商品追加で生成した画像を削除
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image);
        }
    }

    //ログイン中、カートに追加せずにregConfirmにGETアクセスした場合
    public function test_error(): void
    {
        //ユーザ作成
        $user = User::factory()->create();

        //カートに追加せずに購入ボタンを押下
        $response = $this->actingAs($user)->get(route('cart.regConfirm'));

        //エラー画面に遷移すること
        $response->assertStatus(200)->assertViewIs('no');
    }

    //ゲスト状態でカートに追加せずにbuyerにGETアクセスした場合
    public function test_error2(): void
    {
        //カートに商品を追加せずに登録しないで購入するボタンを押下
        $response = $this->get(route('cart.buyer'));

        //エラー画面に遷移すること
        $response->assertStatus(200)->assertViewIs('no');
    }
*/
    //カートを全削除できること
    public function test_cart_all_delete(): void
    {
        //商品を2つ追加する
        $product = Product::factory()->create();
        $product2 = Product::factory()->create();

        //1つ目の商品をカートに入れる
        $response = $this->post(route('vue.cart.store'), [
            'id' => $product->id,
        ]);
        
        //カートに追加されていること
        $response->assertSessionHas('carts', function ($value) use ($product) {
            return $value[0]['product']->id === $product->id;
        });

        //2つ目の商品をカートに入れる
        $response = $this->post(route('vue.cart.store'), [
            'id' => $product2->id,
        ]);

        //カートに追加されていること
        $response->assertSessionHas('carts', function ($value) use ($product2) {
            return $value[1]['product']->id === $product2->id;
        });

        //全削除ボタンを押下する
        $response = $this->delete(route('vue.cart.allDelete'));

        //カート一覧にリダイレクトすること
        $response->assertStatus(302)->assertRedirect(route('vue.cart.index'));

        //カートの中身がクリアされていること
        $response->assertSessionMissing('carts');

        //商品追加で生成した画像を削除
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image); // 画像を削除します
        }
        if ($this->checkFileExists(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product2->image)) {
            unlink(storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product2->image); // 画像を削除します
        }
    }
        
    public function checkFileExists($path) {
        if (\File::exists($path) && !is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }

}