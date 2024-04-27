<?php

namespace App\UseCases\Product;

use App\Models\Product;
use App\UseCases\Image\CheckFileExists;
use App\UseCases\Image\MakeImageFileName;
use App\UseCases\Image\SaveImage;
use App\UseCases\Product\UpdateProduct;
use App\UseCases\Product\GetImageNameFromId;

class UpdateAction
{
    public function __construct(
        CheckFileExists $checkFileExists,
        MakeImageFileName $makeImageFileName,
        SaveImage $saveImage,
        UpdateProduct $updateProduct,
        GetImageNameFromId $getImageNameFromId,
        )//use必須
    {
        $this->checkFileExists = $checkFileExists;
        $this->makeImageFileName = $makeImageFileName;
        $this->saveImage = $saveImage;
        $this->updateProduct = $updateProduct;
        $this->getImageNameFromId = $getImageNameFromId;
    }

    public function __invoke($request, string $id)
    {
        //
        //nullだとupdateAction関数を呼び出す時にエラーになる
        $imageFileName = "";

        //画像を更新する場合
        if (!empty($request->image)) {

            $imageName = ($this->getImageNameFromId)($id);

            //更新前の画像のフルパス
            $oldImageFullPath = storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $imageName;

            //更新前の画像を削除する
            if (($this->checkFileExists)($oldImageFullPath)) {
                unlink($oldImageFullPath);
            }

            //商品画像を保存する時のファイル名を作成する。ファイル名の衝突対策でランダム文字列を付加する
            $imageFileName = ($this->makeImageFileName)($request->image->getClientOriginalName());

            //画像を保存する
            ($this->saveImage)($request->image, \Config::get('filepath.imageSaveFolder'), $imageFileName);
        }
        
        //DBを更新する
        ($this->updateProduct)($id, $request->name, $request->cost, $imageFileName);

    }
}
