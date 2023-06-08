<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ContactRequest;

//イベント使用
use App\Events\ContactSended;

class ContactController extends Controller
{
    //お問い合わせフォームトップページ
    public function index ()
    {
        return view('contact.index');
    }

    //問い合わせフォームの入力値を受け取って確認画面を表示する
    public function confirm (ContactRequest $request)
    {
        //フォームの入力値を確認用ビューに渡す
        $inputs = $request->all();
        return view('contact.confirm', [ 'inputs' => $inputs ]);
    }

    public function send (ContactRequest $request)
    {
        //フォームのname="action"の値を取得(送信するか確認画面にするかの判定)
        $action = $request->input('action');

        //フォームのaction以外の値を取得
        /*以下の配列を取得できる。
        array(4) {
            ["_token"]=> string(40) "zTXAuvVjQ67RH8KNdnx6a39Y88NCmjmrIT42KxgO"
            ["email"]=> string(3) "test@test.com" 
            ["title"]=> string(1) "test-title" 
            ["body"]=> string(1) "test-body" 
        }
        */
        $inputs = $request->except('action');

        if ( $action === 'submit') {

            //問合せ送信処理のイベントに渡す
            event(new ContactSended($inputs));

            //二重送進対策でトークン再発行
            $request->session()->regenerateToken();

            //送進完了のビューを表示
            return view('contact.thanks');

        } else {

            //前画面に戻る
            return redirect()
            ->route('contact.index')
            ->without($inputs);

        }

    }
}
