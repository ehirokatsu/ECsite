<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Product;

class VueCartController extends Controller
{
    //
    public function index (Request $request)
    {
        //セッションからカート内容を取得
        $carts = $request->session()->get('carts');
        
        //$cartがnullの場合はView側で表示処理を分ける
        return Inertia::render('Cart/Index', [
            'carts' => $carts,
        ]);
    }

    public function store (Request $request)
    {
        //現在のカート内容を取得
        $carts = $request->session()->get('carts');

        /*
        //既にカートに追加されている商品ならエラー画面を表示する
        if (!empty($carts) 
            && ($this->checkIdExists)($carts, (int)$request->id)
        ) {
            return view('cart.duplication');
        }
            */

        //カートに追加する商品情報を取得
        $product = product::findOrFail($request->id);

        //商品情報をカートに格納する形式にする
        $array = [
            'product' => $product,
            'quantity' => 1,
        ];  
        
        //初回は$cartがnullなので、配列初期化しないとpushできない
        if (empty($carts)) {
            $carts = [
                [
                    'product' => $product,
                    'quantity' => 1,
                ]
            ];  
        } else {
            //カートに追加する
            array_push($carts, $array);
        }

        //カート配列の添え字を０オリジンにする（異常系）
        $carts = array_values($carts);
        
        //カートをsessionに保存する
        $request->session()->put('carts', $carts);

        return redirect()->route('vue.cart.index');
    }
}
