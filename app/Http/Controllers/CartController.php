<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class CartController extends Controller
{
    //
    public function index (Request $request)
    {
        //セッションから値を取得
        $carts = $request->session()->get('cart');

        //$cartがnullの場合はView側で表示処理を分ける
        return view('cart.index', ['carts' => $carts]);

    }
    public function store (Request $request)
    {
        //セッションに値を保存
        $product = product::findOrFail($request->id);

        //現在のカート内容を取得
        $cart = $request->session()->get('cart');

        //初回は$cartがnullなので、配列初期化しないとpushできない
        if (empty($cart)) {
            $cart = [];
        }
        //カートに追加する
        array_push($cart, $product);

        //カートをsessionに保存する
        $request->session()->put('cart', $cart);
        //\Log::info($cart);
        return redirect('/cart');
    }


    public function store2 (Request $request)
    {
        //セッションに値を保存
        $product = product::findOrFail($request->id);

        //現在のカート内容を取得
        $cart = $request->session()->get('cart');

        //追加された商品を取得
        $array = [
            'product' => $product,
        ];  
        
        //カートに追加する
        if (!empty($cart)) {

            array_push($cart, $array);

        } else {

            $cart = [
                [
                    'product' => $product,
                ]
            ];  

        }

        //カートをsessionに保存する
        $request->session()->put('cart', $cart);

        return redirect('/cart');
    }

    public function destroy (Request $request, string $id)
    {

        //現在のカート内容を取得
        $cart = $request->session()->get('cart');

/*
        $index = $this->searchValue($cart, $id);

        //$cart[false]だと$cart[0]と同じ意味になる。よって先頭要素が削除されてしまう。
        //index !== falseを加えてはじくようにする。
        if (isset($cart[$index]) && $index !== false) {
            unset($cart[$index]);
        }
        $cart = array_values($cart);
*/
        $cart = $this->removeCartItemById($cart, $id);

        //カートをsessionに保存する
        $request->session()->put('cart', $cart);

        return redirect('/cart');
        
    }

    public function searchValue($array, $value)
    {
        foreach ($array as $index => $subArray) {
            $subIndex = array_search($value, $subArray);
            if ($subIndex !== false) {
                return $index;
            }
        }
        return false;
    }

    public function removeCartItemById($cart, $id) {
        foreach ($cart as $key => $item) {
            if ($item->id == $id) {
                unset($cart[$key]);
            }
        }
        return array_values($cart);
    }

    public function confirm (Request $request)
    {
        //ログインしていない場合、ログイン画面にリダイレクトする
        if (\Auth::guest()) {

            //セッションにログイン後のリダイレクト先を指定する
            $request->session()->put('redirect_to', '/cart/confirm');

            return redirect()->guest('login');
        }
        
        return view('cart.confirm');

    }

    public function complete (Request $request)
    {

        //カートを空にする
        $request->session()->forget('cart'); 

        return view('cart.complete');

    }

    public function buy (Request $request)
    {

        return view('cart.buy');

    }

    public function buyConfirm (Request $request)
    {

        $inputs = $request->all();
        return view('cart.buyConfirm', ['inputs' => $inputs]);

    }
}
