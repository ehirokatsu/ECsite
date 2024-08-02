<?php

namespace App\UseCases\Product;

use App\Models\Product;

class UpdateProduct
{
    //nameなどは更新しない場合もあるので、デフォルトnullにする
    public function __invoke(String $id, String $name=null, String $cost=null, String $image=null): void
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
