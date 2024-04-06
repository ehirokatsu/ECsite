<?php

namespace App\UseCases\Product;

use App\Models\Product;
use App\UseCases\Image\SaveImage;
use App\UseCases\Image\MakeImageFileName;
use App\UseCases\Product\SaveProduct;


class StoreAction
{
    public function __construct(
        SaveImage $saveImage,
        MakeImageFileName $makeImageFileName,
        SaveProduct $saveProduct,
        )//use必須
    {
        $this->saveImage = $saveImage;
        $this->makeImageFileName = $makeImageFileName;
        $this->saveProduct = $saveProduct;
    }

    public function __invoke($request): void
    {
        //
        //商品画像を保存する時のファイル名を作成する。ファイル名の衝突対策でランダム文字列を付加する
        $imageFileName = ($this->makeImageFileName)($request->image->getClientOriginalName());
    
        //画像を保存する
        ($this->saveImage)($request->image, \Config::get('filepath.imageSaveFolder'), $imageFileName);

        //フォームからDBへセット
        ($this->saveProduct)($request->name, $request->cost, $imageFileName);

    }
}
