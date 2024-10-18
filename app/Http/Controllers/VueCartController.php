<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\UseCases\Cart\CheckIdExists;
use App\UseCases\Cart\UpdateQuantityById;
use App\UseCases\Cart\RemoveCartItemsById;

use App\Http\Requests\QuantityRequest;

class VueCartController extends Controller
{
    public function __construct(
        CheckIdExists $checkIdExists,
        UpdateQuantityById $updateQuantityById,
        RemoveCartItemsById $removeCartItemsById,
        )//use必須
    {
        $this->checkIdExists = $checkIdExists;
        $this->updateQuantityById = $updateQuantityById;
        $this->removeCartItemsById = $removeCartItemsById;
    }
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

        //カートに追加されている商品なら、個数を増やして上書きする。
        //商品一覧でも個数を選択してカートに入れるようにする。

        //既にカートに追加されている商品なら個数を増やして上書きする。
        if (!empty($carts) 
            && ($this->checkIdExists)($carts, (int)$request->id)
        ) {
            //個数を増やす処理。
            //$cart,$idを引数にして個数を取得する。
            $newQuantity = 0;

            foreach ($carts as $array) {
                if ($array['product']->id === (int)$request->id) {
                    $newQuantity = $array['quantity'] + 1;
                    break;
                }
            }
            $carts = ($this->updateQuantityById)($carts, (int)$request->id, $newQuantity);

        } else {

            //カートに追加する商品情報を取得
            $product = product::findOrFail($request->id);

            //商品情報をカートに格納する形式にする
            $array = [
                'product' => $product,
                'quantity' => 1,
            ];  
            
            //初回は$cartがnullなので、配列初期化しないとpushできない
            if (empty($carts)) {

                $carts = [$array]; 

            } else {
                //カートに追加する
                array_push($carts, $array);
            }

            //カート配列の添え字を０オリジンにする（異常系）
            $carts = array_values($carts);
            
        }


        //カートをsessionに保存する
        $request->session()->put('carts', $carts);

        return redirect()->route('vue.cart.index');
    }

    public function destroy (Request $request, string $id)
    {
        //現在のカート内容を取得
        $carts = $request->session()->get('carts');

        //カートから指定したIDを持つ要素を削除する
        $carts = ($this->removeCartItemsById)($carts, $id);

        //カートをsessionに保存する
        $request->session()->put('carts', $carts);

        return redirect()->route('vue.cart.index');
        
    }

    public function quantityUpdate (QuantityRequest $request, int $id)
    {
        //現在のカート内容を取得
        $carts = $request->session()->get('carts');

        //更新したい個数を取得
        $newQuantity = $request->quantity;

        //個数を更新する
        $carts = ($this->updateQuantityById)($carts, $id, $newQuantity);

        //カートをsessionに保存する
        $request->session()->put('carts', $carts);

        return redirect()->route('vue.cart.index');
    }

    public function allDelete (Request $request)
    {
        //カートを空にする
        $request->session()->forget('carts');

        return redirect()->route('vue.cart.index');
    }

    public function purchaseConfirm (Request $request)
    {
        //URL指定などカートが空でアクセスしたら、不正検出画面を表示する
        if (empty($request->session()->get('carts'))) {
            return view('no');
        }

        //ログインしていない場合、ログイン画面にリダイレクトする
        if (\Auth::guest()) {

            //セッションにログイン後のリダイレクト先を指定する
            $request->session()->put('redirect_to', '/vue/cart/purchaseConfirm');

            return redirect()->guest('login');
        }
        
        /*ユーザー情報はauthの認証情報から、カートは元々セッションに格納しているので省略
        $user = auth()->user();
        $carts = $request->session()->get('carts');
        $request->session()->flash('user', $user);
        $request->session()->flash('carts', $carts);
        */

        /*login処理ではリダイレクトさせるので、下記のrenderでは渡さない方法にした。
        return Inertia::render('Cart/PurchaseConfirm', [
            'carts' => $carts,
            'user' => $user,
        ]);
        */

        return Inertia::render('Cart/PurchaseConfirm');
    }
}
