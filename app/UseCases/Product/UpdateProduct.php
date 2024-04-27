<?php

namespace App\UseCases\Product;

use App\Models\Product;

class UpdateProduct
{
    public function __invoke(String $id, String $name, String $cost, String $image): void
    {
        //
        $product = product::findOrFail($id);
        $product->name = $name;
        $product->cost = $cost;

        if ($image != "") {
            $product->image = $image;
        }
        
        $product->save();
    }
}
