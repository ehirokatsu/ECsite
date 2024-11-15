<?php

namespace App\Repositories;

use App\Models\Product;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository implements ProductRepositoryInterface
{
    public function findOrFail(string $id)
    {
        try {
            return Product::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            // Eloquent固有の例外をアプリケーション固有の例外に変換
            throw new ProductNotFoundException("Product not found with ID: $id", 0, $e);
        }
    }

    public function all()
    {
        return Product::all();
    }
}