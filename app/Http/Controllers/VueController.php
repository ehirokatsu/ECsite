<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\CreateConfirmRequest;
use App\Models\Product;
use Inertia\Inertia;
use App\UseCases\Image\SaveImage;
use App\UseCases\Image\MakeImageFileName;
use App\UseCases\Product\StoreAction;
use App\UseCases\Product\UpdateAction;
use App\UseCases\Product\GetImageNameFromId;
use App\UseCases\Product\IndexAction;

class VueController extends Controller
{
    
    //
    public function __construct(
        SaveImage $saveImage,
        MakeImageFileName $makeImageFileName,
        StoreAction $storeAction,
        UpdateAction $updateAction,
        GetImageNameFromId $getImageNameFromId,
        IndexAction $indexAction,
        )//use必須
    {
        $this->saveImage = $saveImage;
        $this->makeImageFileName = $makeImageFileName;
        $this->storeAction = $storeAction;
        $this->updateAction = $updateAction;
        $this->getImageNameFromId = $getImageNameFromId;
        $this->indexAction = $indexAction;
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
        $product = product::findOrFail($id);
        return Inertia::render('edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, string $id)
    {
        //nullだとupdateAction関数を呼び出す時にエラーになる
        $imageFileName = "";

        //画像を更新する場合
        if (!empty($request->image)) {

            $imageName = ($this->getImageNameFromId)($id);

            //更新前の画像のフルパス
            $oldImageFullPath = storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $imageName;

            //更新前の画像を削除する
            if ($this->checkFileExists($oldImageFullPath)) {
                unlink($oldImageFullPath);
            }

            //商品画像を保存する時のファイル名を作成する。ファイル名の衝突対策でランダム文字列を付加する
            $imageFileName = ($this->makeImageFileName)($request->image->getClientOriginalName());

            //画像を保存する
            ($this->saveImage)($request->image, \Config::get('filepath.imageSaveFolder'), $imageFileName);
        }
        
        //DBを更新する
        ($this->updateAction)($id, $request->name, $request->cost, $imageFileName);


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
