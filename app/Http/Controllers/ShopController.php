<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\CreateConfirmRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Requests\Product\EditConfirmRequest;
use App\Models\Product;
use Gate;
use Carbon\Carbon;
use Inertia\Inertia;
use App\UseCases\Image\SaveImage;
use App\UseCases\Image\MakeImageFileName;
use App\UseCases\Product\StoreAction;

class ShopController extends Controller
{
    public function __construct(
        SaveImage $saveImage,
        MakeImageFileName $makeImageFileName,
        StoreAction $storeAction
        )//use必須
    {
        $this->saveImage = $saveImage;
        $this->makeImageFileName = $makeImageFileName;
        $this->storeAction = $storeAction;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::all();
        $param = ['products' => $products];
        return view('index', $param);

        /*
        $param2 = [
            'test' => 100,
        ];
        return Inertia::render('index', $param2);
        */

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('create');
    }

    public function createConfirm(CreateConfirmRequest $request)
    {
        //フォーム内容を取得する
        $inputs = $request->all();

        //商品画像を一時保存する時のファイル名を作成する。ファイル名の衝突対策でランダム文字列を付加する
        $tmpImageFileName = ($this->makeImageFileName)($request->image->getClientOriginalName());
    
        //一時保存フォルダに画像を保存する
        ($this->saveImage)($request->image, \Config::get('filepath.imageTmpSaveFolder'), $tmpImageFileName);

        //画像ファイル名をセッションに保存する（テストコードで取得可能にする為）
        $request->session()->put('tmpImageFileName', $tmpImageFileName);

        $param = [
            'inputs' => $inputs,
        ];

        return view('createConfirm', $param);
    }

    /**
     * Store a newly created resource in storage.
     */
    //フォームリクエストを入れると、エラー時リダイレクトはcreateConfirmにGETでアクセスするがrouteにないのでエラーになる
    public function store(StoreRequest $request)
    {
        //修正する場合にViewに渡すフォーム値
        $inputs = $request->except('action');

        //一時保存した画像ファイル名
        $srcImageFileName = $request->session()->get('tmpImageFileName');

        //一時保存した画像ファイルパス
        $srcImageFullPath = storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder')) . $srcImageFileName;

        //登録する場合
        if ($request->action === 'submit') {

            //保存する画像ファイル名
            $dstImageFileName = $srcImageFileName;

            //保存する画像ファイルパス 
            $dstImageFullPath = storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $dstImageFileName;

            //商品レコードを保存する
            ($this->storeAction)($request->name, $request->cost, $dstImageFileName);

            //一時保存した画像を移動する
            if ($this->checkFileExists($srcImageFullPath)) {//このバリデーションも別だし？
                \File::move($srcImageFullPath, $dstImageFullPath);
            }

            //セッションに保存した画像ファイル名が不要になるので削除する
            $request->session()->forget('tmpImageFileName');

            return redirect('/');

        //修正する場合
        } else {

            //セッションに保存した画像ファイル名が不要になるので削除する
            $request->session()->forget('tmpImageFileName');
        
            //tmpに画像がない場合は、不正検出画面に遷移させる必要がある。リダイレクトとかで。フォームリクエストは使えない
            //それはドメインバリデーション？どこでやる？
            
            //一時保存した画像を削除する
            if ($this->checkFileExists($srcImageFullPath)) {
                unlink($srcImageFullPath);
            }

            //$request->session()->flashInput($inputs);

            //ファイルも選択状態にして戻すにはどうしたらいいか？→無理そう
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

    public function editConfirm(EditConfirmRequest $request, string $id)
    {
        //編集する商品レコード
        $product = Product::findOrFail($id);

        //入力内容
        $inputs = $request->all();

        //画像を更新する場合
        if (!empty($request->image)) {
            
            //現在日時を取得
            $now = Carbon::now()->format('Y_m_d_H_i_s');

            //商品画像を一時保存する時のファイル名を作成する。ファイル名の衝突対策でランダム文字列を付加する
            $tmpImageFileName = $now . '_' . \Str::random(5) . '_' . $request->image->getClientOriginalName();

            //一時保存フォルダに画像を保存する
            $request->image->storeAs(\Config::get('filepath.imageTmpSaveFolder'), $tmpImageFileName);
            
            //画像ファイル名をセッションに保存する（テストコードで取得可能にする為）
            $request->session()->put('tmpImageFileName', $tmpImageFileName);

        //画像を更新しない場合
        } else {

            $srcImageFileName = $request->session()->get('tmpImageFileName');

            //セッションと一時画像ファイルが残っていたら削除する
            if (!empty($srcImageFileName)) {
                
                $srcImageFullPath = storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder')) . $srcImageFileName;

                if ($this->checkFileExists($srcImageFullPath)) {
                    unlink($srcImageFullPath);
                }

                $request->session()->forget('tmpImageFileName');
            }


        }

        $param = [
            'product' => $product,              //update処理時の宛先ID用
            'inputs' => $inputs,                //nameとcost表示用
        ];

        return view('editConfirm', $param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        //戻る処理でも使用するのでif文前で取得する
        $product = product::findOrFail($id);
        //$inputs = $request->except('action');
        
        //確定ボタン押下
        if ($request->action === 'submit') {

            //nameを更新する場合
            $product->name = $request->name;

            //costを更新する場合
            $product->cost = $request->cost;

            //画像を更新する場合
            if (!empty($request->session()->get('tmpImageFileName'))) {

                //一時保存した画像ファイル名
                $srcImageFileName = $request->session()->get('tmpImageFileName');

                //一時保存した画像のフルパス
                $srcImageFullPath = storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder')) . $srcImageFileName;

                //保存する画像のフルパス
                $dstImageFullPath = storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $srcImageFileName;

                //更新前の画像のフルパス
                $oldImageFullPath = storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image;

                //更新前の画像を削除する
                if ($this->checkFileExists($oldImageFullPath)) {
                    unlink($oldImageFullPath);
                }

                //DBの画像ファイル名を更新する
                $product->image = $srcImageFileName;

                //セッションに保存した画像ファイル名が不要になるので削除する
                $request->session()->forget('tmpImageFileName');

                //一時保存した画像を移動する
                if ($this->checkFileExists($srcImageFullPath)) {
                    \File::move($srcImageFullPath, $dstImageFullPath);
                }
            }

            $product->save();

            return redirect("/");

        } else {//修正ボタン押下

            if (!empty($request->session()->get('tmpImageFileName'))) {

                //一時保存した画像ファイル名
                $srcImageFileName = $request->session()->get('tmpImageFileName');

                //一時保存した画像のフルパス
                $srcImageFullPath = storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder')) . $srcImageFileName;

                //確認用として保存した画像を削除する
                if ($this->checkFileExists($srcImageFullPath)) {
                    unlink($srcImageFullPath);
                }

                $request->session()->forget('tmpImageFileName');
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
        $imageFullPath = storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image;
        
        //商品画像を削除する
        if ($this->checkFileExists($imageFullPath)) {

            unlink($imageFullPath);

        } else {

            //エラーメッセージをViewに渡して表示出来るようにしたい
            //return redirect()->route('no');
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
