<?php

namespace App\UseCases\Product;

use App\Models\Product;
use App\UseCases\Image\CheckFileExists;
use App\Repositories\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Config;

class DeleteAction
{
    /**
     * コンストラクタ
     *
     * @param CheckFileExists $checkFileExists 商品画像の存在確認を行うクラスのインスタンス
     */
    public function __construct(
        CheckFileExists $checkFileExists,
        ProductRepositoryInterface $productRepositoryInterface
        )//use必須
    {
        $this->checkFileExists = $checkFileExists;
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * 商品削除の実行メソッド
     *
     * @param string $id 削除対象商品のID
     */
    public function __invoke(string $id)
    {
        try {
            //削除対象のレコードを取得する
            //リポジトリクラスにしているが、Eloquentを完全に隠蔽できていない。
            //隠蔽すると下記のdeleteメソッドや専用のProductデータクラスを作成する必要があり手間なので、中途半端だが現状でよしにする。
            //テストコードは、Eloquentをモックできないので、DeleteAction分は未作成。
            $product = $this->productRepositoryInterface->findOrFail($id);

            //商品画像のフルパスを取得する
            $imageFullPath = storage_path('app/' . Config::get('filepath.imageSaveFolder')) . $product->image;
            
            //商品画像を削除する
            if (($this->checkFileExists)($imageFullPath)) {
                unlink($imageFullPath);
            } else {
                throw new Exception("Product image not found" . $imageFullPath);
            }

            //商品レコードを削除する
            $product->delete();

        } catch (Exception $e) {
            throw new Exception("error: " . $e->getMessage(), 0, $e);
        }
    }
}
