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

        } catch (\Exception $e) {

            throw $e;
            
        }
    }
}
