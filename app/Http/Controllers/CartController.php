<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

use App\Http\Requests\QuantityRequest;
use App\Http\Requests\BuyerRequest;

class CartController extends Controller
{
    //
    public function index (Request $request)
    {
        //セッションからカート内容を取得
        $carts = $request->session()->get('carts');

        //$cartがnullの場合はView側で表示処理を分ける
        return view('cart.index', ['carts' => $carts]);

    }
    public function store (Request $request)
    {
        //現在のカート内容を取得
        $carts = $request->session()->get('carts');

        //既にカートに追加されている商品ならエラー画面を表示する
        if (!empty($carts) 
            && $this->checkIdExists($carts, (int)$request->id)
        ) {
            return view('cart.duplication');
        }

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

        return redirect('/cart');
    }

    public function destroy (Request $request, string $id)
    {
        //現在のカート内容を取得
        $carts = $request->session()->get('carts');

        //\Log::info('log開始');
        //カートから指定したIDを持つ要素を削除する
        $carts = $this->removeCartItemsById($carts, $id);

        //カート配列の添え字を０オリジンにする
        $carts = array_values($carts);

        //カートをsessionに保存する
        $request->session()->put('carts', $carts);

        return redirect('/cart');
        
    }

    /*
    array_filter()の1つ目の引数はフィルタリングする配列で、2つ目の引数は
    コールバック関数です。配列の各要素に対して実行され、その要素が新しい配列に含まれるべきかどうかを判断する。
    よって、$item = [
        'product' => $product,
        'quantity' => 1,
        ]
    となる。$item['product']でProductインスタンスにアクセスし、->idでID要素を参照する
    */
    public function removeCartItemsById($array, $id)
    {
        return array_filter($array, function($item) use ($id) {
            return $item['product']->id != $id;
        });
    }

    public function confirm (Request $request)
    {
        //URL指定などカートが空でアクセスしたら、不正検出画面を表示する
        if (empty($request->session()->get('carts'))) {
            return view('no');
        }

        //ログインしていない場合、ログイン画面にリダイレクトする
        if (\Auth::guest()) {

            //セッションにログイン後のリダイレクト先を指定する
            $request->session()->put('redirect_to', '/cart/confirm');

            return redirect()->guest('login');
        }
        
        return view('cart.confirm');

    }

    public function complete (BuyerRequest $request)
    {
        //フォームのname="action"の値を取得(送信するか確認画面にするかの判定)
        $action = $request->input('action');
        
        //フォームのaction以外の値を取得
        $inputs = $request->except('action');

        if ( $action === 'submit') {

            //購入後の処理はここに書く


            //カートを空にする
            $request->session()->forget('carts'); 

            return view('cart.complete');

        } else {

            //入力した値を次のリクエストまでの間だけセッションに保存する
            $request->session()->flashInput($inputs);
            
            //前画面に戻る。リダイレクト先でold関数を使ってリクエストの入力値を取得する
            return redirect()->route('cart.buyer')->withInput();
        }

    }

    public function buyer (Request $request)
    {
        //URL指定などカートが空でアクセスしたら、不正検出画面を表示する
        if (empty($request->session()->get('carts'))) {
            return view('no');
        }

        return view('cart.buyer');
    }

    public function buyerConfirm (BuyerRequest $request)
    {
        $inputs = $request->all();

        return view('cart.buyerConfirm', ['inputs' => $inputs]);
    }

    public function register (Request $request)
    {
        //ログインしていない場合、ログイン画面にリダイレクトする
        if (\Auth::guest()) {

            //セッションにログイン後のリダイレクト先を指定する
            $request->session()->put('redirect_to_reg', '/cart/confirm');

            return redirect()->guest('register');
        }
        
        return view('cart.confirm');
    }

    public function allDelete (Request $request)
    {
        //カートを空にする
        $request->session()->forget('carts');

        return redirect('/cart');
    }


    public function quantityUpdate (QuantityRequest $request, int $id)
    {
        //現在のカート内容を取得
        $carts = $request->session()->get('carts');

        //更新したい個数を取得
        $newQuantity = $request->quantity;

        //個数を更新する
        $carts = $this->updateQuantityById($carts, $id, $newQuantity);

        //カートをsessionに保存する
        $request->session()->put('carts', $carts);

        return redirect('/cart');
    }
    
    public function updateQuantityById($array, $id, $newQuantity)
    {
        foreach ($array as &$item) {
            //\Log::info('product->id=' . $item['product']->id);
            if ($item['product']->id === $id) {
                $item['quantity'] = $newQuantity;
                //\Log::info('quantity=' . $item['quantity']);
            
                break;
            }
        }
        return $array;
    }
    public function checkIdExists($array, $id)
    {
        foreach ($array as $item) {
            if ($item['product']->id === $id) {
                return true;
            }
        }
        return false;
    }
}
