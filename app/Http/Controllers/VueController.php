<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\CreateConfirmRequest;
use App\Models\Product;
use Carbon\Carbon;
use Inertia\Inertia;
use App\UseCases\Image\SaveImage;


class VueController extends Controller
{
    
    //
    public function __construct(SaveImage $saveImage)//use必須
    {
        $this->saveImage = $saveImage;
    }
    
    //
    public function index()
    {
        //コンストラクタインジェクションの場合、__inovkeを呼び出すには以下にように記載。
        ($this->saveImage)();
        $this->saveImage->__invoke();
        //メソッドインジェクションなら以下だけで良い
        //$saveImage();

        $products = Product::all();

        return Inertia::render('index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('create');
    }

    public function store(CreateConfirmRequest $request)
    {
        //空の商品モデルを生成
        $product = new Product;

        //現在日時を取得
        $now = Carbon::now()->format('Y_m_d_H_i_s');

        //商品画像を保存する時のファイル名を作成する。ファイル名の衝突対策でランダム文字列を付加する
        $imageFileName = $now . '_' . \Str::random(5) . '_' . $request->image->getClientOriginalName();

        //画像を保存する
        $request->image->storeAs(\Config::get('filepath.imageSaveFolder'), $imageFileName);

        //フォームからDBへセット
        $product->name = $request->name;
        $product->cost = $request->cost;
        $product->image = $imageFileName;
        $product->save();

        //$products = Product::all();

        return redirect('/vue');
    }


    public function edit(string $id)
    {
        $product = product::findOrFail($id);
        return Inertia::render('edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, string $id)
    {
        //dd($request->name);
        $product = product::findOrFail($id);

        //nameを更新する場合
        $product->name = $request->name;

        //costを更新する場合
        $product->cost = $request->cost;

        if (!empty($request->image)) {

            //更新前の画像のフルパス
            $oldImageFullPath = storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image;

            //更新前の画像を削除する
            if ($this->checkFileExists($oldImageFullPath)) {
                unlink($oldImageFullPath);
            }

            //現在日時を取得
            $now = Carbon::now()->format('Y_m_d_H_i_s');

            //商品画像を一時保存する時のファイル名を作成する。ファイル名の衝突対策でランダム文字列を付加する
            $imageFileName = $now . '_' . \Str::random(5) . '_' . $request->image->getClientOriginalName();

            //一時保存フォルダに画像を保存する
            $request->image->storeAs(\Config::get('filepath.imageSaveFolder'), $imageFileName);

            //DBの画像ファイル名を更新する
            $product->image = $imageFileName;
        }
        $product->save();

        return redirect('/vue');

    }

    public function destroy(string $id) {

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

        return redirect()->route('vue.index', $parameters = [], $status = 303, $headers = []);
    }

    public function checkFileExists($path) {
        if (\File::exists($path) && !is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }
}
