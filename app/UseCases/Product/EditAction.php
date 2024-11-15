<?php

namespace App\UseCases\Product;

use App\Repositories\ProductRepositoryInterface;
use App\Exceptions\ProductNotFoundException;

class EditAction
{
    protected $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function __invoke(string $id)
    {
        try {
            return $this->productRepositoryInterface->findOrFail($id);
        } catch (ProductNotFoundException $e) {
            throw new ProductNotFoundException("Product not found with ID: $id", 0, $e);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }
}