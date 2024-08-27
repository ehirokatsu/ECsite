<?php

namespace App\UseCases\Cart;

class CheckIdExists
{

    public function __invoke($array, $id): bool
    //public function test(): void
    {
        //
        foreach ($array as $item) {
            if ($item['product']->id === $id) {
                return true;
            }
        }
        return false;
    }
}
