<?php

namespace App\UseCases\Product;

use App\Models\Product;

class SearchAction
{
    public function __construct()//use必須
    {

    }

    public function __invoke($query)
    {
        //
        return $products = Product::where('name', 'like', '%' . $query . '%')->get();

    }
}
