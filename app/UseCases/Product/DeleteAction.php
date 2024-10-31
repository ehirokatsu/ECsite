<?php

namespace App\UseCases\Product;

use App\Models\Product;
use App\UseCases\Image\CheckFileExists;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\ProductImageNotFoundException;

class DeleteAction
{
    public function __construct(
        CheckFileExists $checkFileExists,
        )//use必須
    {
        $this->checkFileExists = $checkFileExists;
    }

    public function __invoke(string $id)
    {
        try {
            //削除対象のレコードを取得する
            //Failの場合はModelNotFoundException をスローします。
            $product = product::findOrFail($id);

            //商品画像のフルパスを取得する
            $imageFullPath = storage_path('app/' . \Config::get('filepath.imageSaveFolder')) . $product->image;
            
            //商品画像を削除する
            if (($this->checkFileExists)($imageFullPath)) {
                unlink($imageFullPath);
            } else {
                throw new ProductImageNotFoundException();
            }

            //商品レコードを削除する
            $product->delete();
            
        } catch (ModelNotFoundException $e) {
            throw new ProductNotFoundException();
        }
    }
}
