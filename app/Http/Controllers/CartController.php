<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function index (Request $request)
    {
        //セッションから値を取得
        $carts = $request->session()->get('cart');

        return view('cart', ['carts' => $carts]);

    }

    public function store (Request $request)
    {
        //セッションに値を保存
        $id = $request->id;

        //現在のカート内容を取得
        $cart = $request->session()->get('cart');

        //追加された商品を取得
        $array = [
            'id' => $id,
        ];  
        
        //カートに追加する
        if (!empty($cart)) {

            array_push($cart, $array);

        } else {

            $cart = [
                [
                    'id' => $id,
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


        $index = $this->searchValue($cart, $id);

        //$cart[false]だと$cart[0]と同じ意味になる。よって先頭要素が削除されてしまう。
        //index !== falseを加えてはじくようにする。
        if (isset($cart[$index]) && $index !== false) {
            unset($cart[$index]);
        }
        $cart = array_values($cart);

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

}
