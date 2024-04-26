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
        //
        return $products = Product::all();

    }
}
