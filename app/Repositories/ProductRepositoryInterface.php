<?php

namespace App\Repositories;

Interface ProductRepositoryInterface
{
    public function findOrFail(string $id);
    public function all();
}