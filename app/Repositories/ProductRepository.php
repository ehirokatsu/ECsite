<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository
{
    public function findOrFail(string $id)
    {
        return Product::findOrFail($id);
    }
}