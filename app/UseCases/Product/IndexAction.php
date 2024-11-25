<?php

namespace App\UseCases\Product;

use App\Repositories\ProductRepositoryInterface;

class IndexAction
{
    public function __construct(ProductRepositoryInterface $productRepositoryInterface)//use必須
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function __invoke()
    {
        //Productテーブルからの取得はエラーを伴う可能性があるため、try-catchで囲む
        try {

            $products = $this->productRepositoryInterface->all();
            
            if ($products->isEmpty()) {
                throw new \Exception("No products found in the database.");
            }

            return $products;

        //例外は全てExceptionでキャッチして上位にスローする方針
        } catch (\Exception $e) {

            throw $e;
            
        }
    }
}
