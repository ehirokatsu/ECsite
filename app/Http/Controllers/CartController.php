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
        $carts = $request->session()->get('carts');

        //$cartがnullの場合はView側で表示処理を分ける
        return view('cart.index', ['carts' => $carts]);

    }
    public function store (Request $request)
    {
        //セッションに値を保存
        $product = product::findOrFail($request->id);

        //現在のカート内容を取得
        $carts = $request->session()->get('carts');

        //初回は$cartがnullなので、配列初期化しないとpushできない
        if (empty($carts)) {
            $carts = [];
        }
        //カートに追加する
        array_push($carts, $product);

        //カートをsessionに保存する
        $request->session()->put('carts', $carts);
        //\Log::info($carts);
        return redirect('/cart');
    }

    public function destroy (Request $request, string $id)
    {

        //現在のカート内容を取得
        $carts = $request->session()->get('carts');

        $carts = $this->removeCartItemById($carts, $id);

        //カートをsessionに保存する
        $request->session()->put('carts', $carts);

        return redirect('/cart');
        
    }

    public function removeCartItemById($carts, $id) {
        foreach ($carts as $key => $item) {
            if ($item->id == $id) {
                unset($carts[$key]);
            }
        }
        return array_values($carts);
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

    public function complete (Request $request)
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
            return redirect()->route('cart.buy')->withInput();
        }

    }

    public function buy (Request $request)
    {

        //URL指定などカートが空でアクセスしたら、不正検出画面を表示する
        if (empty($request->session()->get('carts'))) {
            return view('no');
        }


        return view('cart.buy');

    }

    public function buyConfirm (Request $request)
    {

        $inputs = $request->all();
        return view('cart.buyConfirm', ['inputs' => $inputs]);

        

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

}
