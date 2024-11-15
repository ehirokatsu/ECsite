<?php

namespace App\UseCases\Product;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Database\QueryException;
use PDOException;

class IndexAction
{
    public function __construct(ProductRepositoryInterface $productRepositoryInterface)//use必須
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function __invoke()
    {
        try {

            $products = $this->productRepositoryInterface->all();
            
            if ($products->isEmpty()) {
                throw new \Exception("No products found in the database.");
            }

            return $products;

        } catch (QueryException $e) {
            // クエリ実行エラーをキャッチ
            throw new \Exception("Database query error: " . $e->getMessage(), 0, $e);

        } catch (PDOException $e) {
            // データベース接続エラーをキャッチ
            throw new \Exception("Database connection error: " . $e->getMessage(), 0, $e);

        } catch (\Exception $e) {

            throw $e;
            
        }
    }
}
