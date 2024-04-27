<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\CreateConfirmRequest;
use App\Models\Product;
use Inertia\Inertia;
use App\UseCases\Product\StoreAction;
use App\UseCases\Product\IndexAction;
use App\UseCases\Product\EditAction;
use App\UseCases\Product\UpdateAction;

class VueController extends Controller
{
    
    //
    public function __construct(
        StoreAction $storeAction,
        IndexAction $indexAction,
        EditAction $editAction,
        UpdateAction $updateAction,
        )//use必須
    {

        $this->storeAction = $storeAction;
        $this->indexAction = $indexAction;
        $this->editAction = $editAction;
        $this->updateAction = $updateAction;
    }
    
    //
    public function index()
    {
        //コンストラクタインジェクションの場合、__inovkeを呼び出すには以下にように記載。
        //($this->saveImage)();
        //$this->saveImage->__invoke();
        //メソッドインジェクションなら以下だけで良い
        //$saveImage();

        $products = ($this->indexAction)();

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
        //保存処理を行う
        ($this->storeAction)($request);

        return redirect('/vue');
    }


    public function edit(string $id)
    {
        $product = ($this->editAction)($id);
        return Inertia::render('edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, string $id)
    {

        ($this->updateAction)($request, $id);
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
