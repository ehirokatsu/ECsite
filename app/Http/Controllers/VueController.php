<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use Carbon\Carbon;
use Inertia\Inertia;


class VueController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();

        return Inertia::render('index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('create');
    }

    public function store(Request $request)
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
    public function checkFileExists($path) {
        if (\File::exists($path) && !is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }
}
