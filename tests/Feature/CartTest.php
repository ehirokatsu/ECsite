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
    public function test_cart_store(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->post('/cart', [
            'id' => $product->id,
        ]);
        //\Log::info($response);
        //$response->dumpSession();
        
        
        $response->assertSessionHas('carts', function ($value) {
            return $value[0]['product']->id === 1;
        });

        $product2 = Product::factory()->create();

        $response = $this->post('/cart', [
            'id' => $product2->id,
        ]);

        $response = $this->delete('/cart/' . $product->id);
        
        //$response->dumpSession();
        $response->assertSessionHas('carts', function ($value) {
            return $value[0]['product']->id !== 1;
        });    
        
        if (file_exists(storage_path('app/public/fake/') . $product->image)) {
            unlink(storage_path('app/public/fake/') . $product->image); // 画像を削除します
        }

        if (file_exists(storage_path('app/public/fake/') . $product2->image)) {
            unlink(storage_path('app/public/fake/') . $product2->image); // 画像を削除します
        }



        //まずはフォームリクエストを完成させること。
        //buyフォーム、カートの個数の上限、

        //カートに商品を追加できること

        //同じ商品をカートに追加すると別画面が表示されカートに入っていないこと

        //カートの商品の個数を変更できること

        //購入する時にログイン画面に遷移すること

        //ログインした後確認画面にリダイレクトすること

        //カートなしで確認画面にアクセスするとエラー画面が表示されること

        //登録なしで購入できること

        //カートなしで登録なしの購入者入力画面にアクセスするとエラー画面が表示されること

        //登録した後確認画面にリダイレクトできること

        //

        //

    }

}
