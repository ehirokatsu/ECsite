<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository implements ProductRepositoryInterface
{ 
    public function findOrFail(string $id)
    {
        return Product::findOrFail($id);
    }

    public function all()
    {
        return Product::all();
    }

}