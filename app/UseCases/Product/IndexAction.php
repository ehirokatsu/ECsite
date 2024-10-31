<?php

namespace App\UseCases\Product;

use App\Models\Product;

class IndexAction
{
    public function __construct()//use必須
    {

    }

    public function __invoke()
    {
        try {
            $products = Product::all();
            
            if ($products->isEmpty()) {
                throw new \Exception("No products found in the database.");
            }

            return $products;

        } catch (\Exception $e) {
            \Log::error('IndexAction Error: ' . $e->getMessage());
            throw new \Exception("Error retrieving products: " . $e->getMessage());
        }
    }
}
