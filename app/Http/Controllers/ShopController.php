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

        if (!empty($request->image)) {

            $imageFileName = 'test.' . $request->image->guessExtension();
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
        //
        $product = product::findOrFail($id);

        //フォームからDBへセット
        if (!empty($request->name)) {
            $product->name = $request->name;
        }
        
        if (!empty($request->cost)) {
            $product->cost = $request->cost;
        }
        
/*
        //dd($request->all());
        if (!empty($request->image)) {
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
        }
*/

        //dd(file_exists(storage_path('app/public/tmp/') . 'test.jpg'));
        \File::move(storage_path('app/public/tmp/') . 'test.jpg', storage_path('app/public/') . $product->id . '.jpg');
        
        $product->save();
        
        return redirect("/");
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
