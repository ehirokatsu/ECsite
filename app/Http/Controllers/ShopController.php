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

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        //空の商品モデルを生成
        $product = new Product;

        //フォームからDBへセット
        $product->name = $request->name;
        $product->cost = $request->cost;
        $product->image = "";
        $product->save();
        
        //画像ファイル名はレコードIDにする
        $imageFileName = $product->id . '.' .$request->image->guessExtension();

        //テストコード実行時は専用のフォルダに保存する
        if (app()->environment('testing')) {

            //画像をストレージに保存する
            $request->image->storeAs('test/', $imageFileName);

        } else {

            $request->image->storeAs('public/', $imageFileName);

        }

        //画像ファイル名をDBにセットする
        $product->image = $imageFileName;
        $product->save();

        return redirect('/');

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
        $product = Product::findOrFail($id);

        $inputs = $request->all();
        //dd($inputs);
        $imageFileName = '';
        if (!empty($request->image)) {

            $imageFileName = \Str::random(10) . '.' . $request->image->guessExtension();
            if (app()->environment('testing')) {

                $request->image->storeAs('test/tmp/', $imageFileName);
            } else {
                $request->image->storeAs('public/tmp/', $imageFileName);
            }
        }

        $param = [
            'product' => $product,
            'inputs' => $inputs,
            'imageFileName' => $imageFileName,
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
        $inputs = $request->except('action');
        //
        if (!empty($request->imageFileName)) {
            $imageFileName = $request->imageFileName;
        } else {
            $imageFileName = 'dummy';
        }

        if ($request->action === 'submit') {

            //フォームからDBへセット
            if (!empty($request->name)) {
                $product->name = $request->name;
            }
            
            if (!empty($request->cost)) {
                $product->cost = $request->cost;
            }
            //dd(storage_path('app/public/tmp/') . $imageFileName);
            \Log::info(storage_path('app/public/tmp/') . $imageFileName);
            \Log::info(public_path('tmp/') . $imageFileName);
            //\Log::info(file_exists(storage_path('app/public/tmp/') . $imageFileName));
            //dd(\Storage::disk('local')->exists('public/tmp/' . $imageFileName));
            //dd(storage_path('app/public/tmp/') . $imageFileName);

            //ファイル名がないとディレクトリのみ指定されるが、それだとtrueになる
            //画像がない時はダミーのファイル名を入れてfalseと判定されるようにする
            if (file_exists(storage_path('app/public/tmp/') . $imageFileName)) {
                \File::move(storage_path('app/public/tmp/') . $imageFileName, storage_path('app/public/') . $product->id . '.jpg');
            }

            $product->save();
            return redirect("/");

        } else {
                
        //戻るボタンを押下した時は、入力値を戻す？画像は削除？
        //変更した項目の色を変えるなどしたい

            if (file_exists(storage_path('app/public/tmp/') . $imageFileName)) {
                \File::move(storage_path('app/public/tmp/') . $imageFileName, storage_path('app/public/') . $product->id . '.jpg');
            }

            //入力した値を次のリクエストまでの間だけセッションに保存する
            $request->session()->flashInput($inputs);
    
            //前画面に戻る。リダイレクト先でold関数を使ってリクエストの入力値を取得する
            return redirect()->route('edit', ['id' => $product->id])->withInput();
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $product = product::findOrFail($id);
        $product->delete();

        return redirect("/");
    }
}
