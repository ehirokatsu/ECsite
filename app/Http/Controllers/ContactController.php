<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//メール送信用
use App\Mail\ContactMail;
//メールクラスを使用する場合に必要
use Mail;

class ContactController extends Controller
{
    //お問い合わせフォームトップページ
    public function index () {

        return view('contact.index');
    }

    //問い合わせフォームの入力値を受け取って確認画面を表示する
    public function confirm (Request $request) {

        //フォームのバリデーション
        $request->validate([
            'email' => 'required|email',
            'title' => 'required',
            'body' => 'required',
        ]);

        //フォームの入力値を確認用ビューに渡す
        $inputs = $request->all();
        return view('contact.confirm', [ 'inputs' => $inputs ]);
    }

    public function send (Request $request) {

        //hiddenで送信された内容をバリデーション（異常系）
        $request->validate([
            'email' => 'required|email',
            'title' => 'required',
            'body' => 'required',
        ]);

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

            //入力されたメールアドレスに確認メールを送進する
            \Mail::to($inputs['email'])->send(new ContactMail($inputs));

            /*
            //Mailクラスのみ使用する場合。内容はSendMailクラスと同じ
            //クロージャ内で$inputsを使うのでuseを使う。
            Mail::send('contact.mail', $inputs, function ($message) use ($inputs) {
                $message->to($inputs['email'])
                ->from('src@example.com')
                ->subject($inputs['title']);
            });

            //Mailクラスでテキストメールを送信する場合
            Mail::send(['text' => 'contact.mail'], $inputs, function ($message) use ($inputs) {
                $message->to($inputs['email'])
                ->from('src@example.com')
                ->subject($inputs['title']);
            });
            */

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
