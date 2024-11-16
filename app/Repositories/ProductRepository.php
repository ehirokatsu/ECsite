<?php

namespace App\Repositories;

use App\Models\Product;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ProductRepository implements ProductRepositoryInterface
{
    public function findOrFail(string $id)
    {
        try {
            return Product::findOrFail($id);

        } catch (ModelNotFoundException $e) {
            // Eloquent固有の例外をアプリケーション固有の例外に変換.Exceptionでもいいかも
            throw new Exception("Product not found with ID: $id." . $e->getMessage(), 0, $e);
            //throw new ProductNotFoundException("Product not found with ID: $id", 0, $e);

        } catch (QueryException $e) {

            throw new \Exception("Database query error: " . $e->getMessage(), 0, $e);

        }
    }

    public function all()
    {
        try {
            return Product::all();

        } catch (ModelNotFoundException $e) {

            throw new Exception("Product not found with ID: $id." . $e->getMessage(), 0, $e);

        } catch (QueryException $e) {

            throw new \Exception("Database query error: " . $e->getMessage(), 0, $e);

        }
        
    }
}