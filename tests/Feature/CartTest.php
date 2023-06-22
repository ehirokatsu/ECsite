<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//factoryで使用
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    //ログイン無しでカートに商品を追加できること
    public function test_cart_store(): void
    {
        //商品を追加
        $product = Product::factory()->create();

        //カートに追加
        $response = $this->post(route('cart.store'), [
            'id' => $product->id,
        ]);
        //\Log::info($response);
        //$response->dumpSession();
        
        //sessionに保存されていること
        $response->assertSessionHas('carts', function ($value) {
            return $value[0]['product']->id === 1;
        });

        //別の商品を追加
        $product2 = Product::factory()->create();

        //カートに追加
        $response = $this->post(route('cart.store'), [
            'id' => $product2->id,
        ]);

        //カートから最初の商品を削除
        $response = $this->delete(route('cart.destroy', ['id' => $product->id]));
        //$response = $this->delete('/cart/' . $product->id);
        //$response->dumpSession();

        //最初の商品がカートから削除されていること
        $response->assertSessionHas('carts', function ($value) use ($product) {
            return $value[0]['product']->id !== $product->id;
        });    
        
        //商品追加で生成した画像を削除
        if (file_exists(storage_path('app/public/fake/') . $product->image)) {
            unlink(storage_path('app/public/fake/') . $product->image); // 画像を削除します
        }
        if (file_exists(storage_path('app/public/fake/') . $product2->image)) {
            unlink(storage_path('app/public/fake/') . $product2->image); // 画像を削除します
        }
    }

    //同じ商品をカートに追加すると別画面が表示されカートに入っていないこと
    public function test_same_product_cart(): void
    {
        //商品を追加
        $product = Product::factory()->create();

        //カートに追加
        $response = $this->post(route('cart.store'), [
            'id' => $product->id,
        ]);
        
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
        if (file_exists(storage_path('app/public/fake/') . $product->image)) {
            unlink(storage_path('app/public/fake/') . $product->image);
        }
    }
        
    //カートの商品の個数を変更できること
    public function test_change_quantity(): void
    {
        //商品を追加
        $product = Product::factory()->create();

        //カートに追加
        $response = $this->post(route('cart.store'), [
            'id' => $product->id,
        ]);

        //個数を変更する
        $updateQuantity = 2;
        $response = $this->put(route('cart.quantityUpdate', ['id' => $product->id]), [
            'quantity' => $updateQuantity,
        ]);

        //$response->dumpSession();
        //sessionに保存されていること
        $response->assertSessionHas('carts', function ($value) use ($product, $updateQuantity) {
            return $value[0]['quantity'] === $updateQuantity;
        });

        //商品追加で生成した画像を削除
        if (file_exists(storage_path('app/public/fake/') . $product->image)) {
            unlink(storage_path('app/public/fake/') . $product->image);
        }
    }

    //購入する時にログイン画面に遷移すること
    public function test_login(): void
    {
        $product = Product::factory()->create();

        $response = $this->post(route('cart.store'), [
            'id' => $product->id
        ]);

        $response = $this->get(route('cart.confirm'));
        $response->assertStatus(302)->assertRedirect(route('login'));

        $user = User::factory()->create();

        $response = $this->post();
    }

    //登録なしで購入者情報画面に遷移すること

    //確認画面に遷移し購入できること


    //ログインした後確認画面にリダイレクトすること。購入できること

    //登録した後確認画面にリダイレクトし購入できること


    //カートなしで確認画面にアクセスするとエラー画面が表示されること

    //カートなしで登録なしの購入者入力画面にアクセスするとエラー画面が表示されること

    //最初からログインした状態でカート入力、購入確認画面、購入できること

    //カートを全削除できること


}
