<?php

namespace App\UseCases\Product;

use App\Models\Product;

class EditAction
{
    public function __construct()//use必須
    {

    }

    public function __invoke(String $id)
    {
        //
        return product::findOrFail($id);

    }
}
