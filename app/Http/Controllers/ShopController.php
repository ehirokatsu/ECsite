<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Gate;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::all();
        $param = ['products' => $products];
        return view('index', $param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('create');
    }

    public function createConfirm(ProductRequest $request)
    {
        $inputs = $request->all();

        $imageFileName = '';//フォームリクエストを使うから空はありえない。削除

        if (!empty($request->image)) {//これも削除？

            $imageFileName = \Str::random(10) . '.' . $request->image->guessExtension();

            if (app()->environment('testing')) {
                
                $request->image->storeAs('test/tmp/', $imageFileName);

            } else {

                $request->image->storeAs('public/tmp/', $imageFileName);

            }
        }//いるなら、elseで例外をいれないと。それならnameとか他のも異常チェックがいるのでは？
        //elseでは不正検出画面にリダイレクト？ここではフォームリクエストで行けそう

        $param = [
            'inputs' => $inputs,
            'imageFileName' => $imageFileName,
        ];

        return view('createConfirm', $param);
    }

    /**
     * Store a newly created resource in storage.
     */
    //フォームリクエストを入れると、エラー時リダイレクトはcreateConfirmにGETでアクセスするがrouteにないのでエラーになる
    public function store(Request $request)
    {
        //空の商品モデルを生成
        $product = new Product;

        $inputs = $request->except('action');

        if (!empty($request->imageFileName)) {//これも削除

            $imageFileName = $request->imageFileName;//srcimage_の名前にしないと分からない

        } else {

            $imageFileName = 'dummy';//ダミーを使わない方法が必要
        }

        $srcImageFullPath = storage_path('app/public/tmp/') . $imageFileName;



        if ($request->action === 'submit') {

            //フォームからDBへセット
            $product->name = $request->name;
            $product->cost = $request->cost;
            $product->image = "";
            $product->save();

            //dd($srcImageFullPath);
            //dd(pathinfo($srcImageFullPath, PATHINFO_EXTENSION));
            //画像ファイル名はレコードIDにする
            //こっちはdstimage_にすること
            $imageFileName = $product->id . '.' . pathinfo($srcImageFullPath, PATHINFO_EXTENSION);

            $dstImageFullPath = storage_path('app/public/') . $imageFileName;

            //画像ファイル名をDBにセットする
            $product->image = $imageFileName;

            $product->save();

            if (file_exists($srcImageFullPath)) {//このバリデーションも別だし？
                \File::move($srcImageFullPath, $dstImageFullPath);
            }

            return redirect('/');

        } else {
        

//tmpに画像がない場合は、不正検出画面に遷移させる必要がある。リダイレクトとかで。フォームリクエストは使えない
//それはドメインバリデーション？どこでやる？
            
            if (file_exists($srcImageFullPath)) {
                unlink($srcImageFullPath);
            }

            $request->session()->flashInput($inputs);

            //ファイルも選択状態にして戻すにはどうしたらいいか？

            return redirect()->route('create')->withInput();

        }
       

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $product = product::findOrFail($id);
        $param = ['product' => $product];
        return view('show', $param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = product::findOrFail($id);
        $param = ['product' => $product];
        return view('edit', $param);

    }

    public function editConfirm(Request $request, string $id)
    {
        //編集する商品レコード
        $product = Product::findOrFail($id);

        //入力内容
        $inputs = $request->all();

        //viewの@ifで判定するため$paramで渡せるように初期化
        $imageFileName = '';

        //画像を一時保存する
        if (!empty($request->image)) {

            //一時保存用にランダムは文字列の名称にする
            $imageFileName = \Str::random(10) . '.' . $request->image->guessExtension();

            //テスト時の保存場所
            if (app()->environment('testing')) {

                $request->image->storeAs('test/tmp/', $imageFileName);

            //通常時の保存場所
            } else {

                $request->image->storeAs('public/tmp/', $imageFileName);

            }
        }

        $param = [
            'product' => $product,              //update処理時の宛先ID用
            'inputs' => $inputs,                //nameとcost表示用
            'imageFileName' => $imageFileName,  //一時保存画像を表示用
        ];

        return view('editConfirm', $param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //戻る処理でも使用するのでif文前で取得する
        $product = product::findOrFail($id);
        //$inputs = $request->except('action');

        //画像を更新する場合、一時保存した画像ファイル名（ランダム文字列＋拡張子）
        if (!empty($request->imageFileName)) {

            $imageFileName = $request->imageFileName;

        //画像を更新しない場合、ダミーの名前
        } else {
            $imageFileName = 'dummy';
        }

        //一時保存した画像のフルパス
        $srcImageFullPath = storage_path('app/public/tmp/') . $imageFileName;

        //商品画像保存用のフルパス
        $dstImageFullPath = storage_path('app/public/') . $product->image;

        //確定ボタン押下
        if ($request->action === 'submit') {

            //フォームからDBへセット
            if (!empty($request->name)) {
                $product->name = $request->name;
            }
            
            if (!empty($request->cost)) {
                $product->cost = $request->cost;
            }
            //dd(storage_path('app/public/tmp/') . $imageFileName);
            //\Log::info(storage_path('app/public/tmp/') . $imageFileName);

            //ファイル名がないとディレクトリのみ指定されるが、それだとtrueになる
            //するとmoveメソッドが実行されるが、画像ファイルがないのでエラーが発生する
            //画像がない時はダミーのファイル名を入れてfalseと判定されるようにする
            if (file_exists($srcImageFullPath)) {
                \File::move($srcImageFullPath, $dstImageFullPath);
            }

            $product->save();

            return redirect("/");

        } else {//修正ボタン押下

            //確認用として保存した画像を削除する
            if (file_exists($srcImageFullPath)) {
                unlink($srcImageFullPath);
            }

            //入力した値を次のリクエストまでの間だけセッションに保存する
            //editビューではProductモデルの値を表示しているのでこの方法では入力値を渡せない
            //$request->session()->flashInput($inputs);
    
            //前画面に戻る。リダイレクト先でold関数を使ってリクエストの入力値を取得する
            return redirect()->route('edit', ['id' => $product->id])->withInput();
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        //削除対象のレコードを取得する
        $product = product::findOrFail($id);

        //商品画像のフルパスを取得する
        $imageFullPath = storage_path('app/public/') . $product->image;

        //商品画像を削除する
        if ($this->checkFileExists($imageFullPath)) {

            unlink($imageFullPath);


        } else {

            //エラーメッセージをViewに渡して表示出来るようにしたい
            return redirect()->route('no');
        }

        //商品レコードを削除する
        $product->delete();

        return redirect("/");
    }


    /** */
    //ファイル名がないとディレクトリのみ指定されるが、それだとtrueになる
    //するとunlinkなどが実行され、ファイルがないので例外発生してしまう
    public function checkFileExists($path) {
        if (\File::exists($path) && !is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }
}
