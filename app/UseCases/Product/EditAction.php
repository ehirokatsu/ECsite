<?php

namespace App\UseCases\Product;

use App\Repositories\ProductRepository;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EditAction
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(string $id)
    {
        try {
            return $this->productRepository->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ProductNotFoundException("Product not found with ID: $id", 0, $e);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }
}