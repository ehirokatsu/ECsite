<?php

namespace App\UseCases\Product;

use App\Repositories\ProductRepositoryInterface;
use App\Exceptions\ProductNotFoundException;
use Exception; 

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

        } catch (Exception $e) {
            throw $e;
        }
    }
}