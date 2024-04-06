<?php

namespace App\UseCases\Product;

use App\Models\Product;

class GetImageNameFromId
{
    public function __invoke(String $id): String
    {
        //
        $product = product::findOrFail($id);
        return $product->image;
    }
}
