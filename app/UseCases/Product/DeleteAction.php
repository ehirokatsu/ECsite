<?php

namespace App\UseCases\Product;

use App\Models\Product;
use App\UseCases\Image\CheckFileExists;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\ProductImageNotFoundException;

class DeleteAction
{
    /**
     * コンストラクタ
     *
     * @param CheckFileExists $checkFileExists 商品画像の存在確認を行うクラスのインスタンス
     */
    public function __construct(
        CheckFileExists $checkFileExists,
        )//use必須
    {
        $this->checkFileExists = $checkFileExists;
    }

    /**
     * 商品削除の実行メソッド
     *
     * @param string $id 削除対象商品のID
     * @throws ProductNotFoundException 商品が存在しない場合にスロー
     * @throws ProductImageNotFoundException 商品画像が存在しない場合にスロー
     */
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
                \Log::error("商品画像が見つかりません: {$imageFullPath}");
                throw new ProductImageNotFoundException();
            }

            //商品レコードを削除する
            $product->delete();
            
        } catch (ModelNotFoundException $e) {

            //ここで商品IDが無い旨のログを出力し、Exceptionでスローし直す？
            //そうすれば呼び出し元のコントローラではExceptionだけの記述で済む、
            //あくまで、ここで使用するメソッドの例外だけ意識する。findOrFailのように
            //ModelNotFoundExceptionをスローするメソッドがある場合は、それを使うのが良い。

            \Log::error("商品が見つかりません: ID {$id}");
            throw new ProductNotFoundException();

        } catch (\Exception $e) {
            
            \Log::error('予期しないエラーが発生しました: ' . $e->getMessage());
            throw $e;
        }
    }
}
