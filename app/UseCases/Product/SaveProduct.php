<?php

namespace App\UseCases\Product;

use App\Models\Product;

class SaveProduct
{
    public function __invoke(String $name, String $cost, String $image): void
    {
        //
        $product = new Product;
        $product->name = $name;
        $product->cost = $cost;
        $product->image = $image;
        $product->save();
    }
}
