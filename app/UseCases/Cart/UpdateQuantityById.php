<?php

namespace App\UseCases\Cart;

class UpdateQuantityById
{

    public function __invoke($array, $id, $newQuantity)
    {
        //
        foreach ($array as &$item) {
            //\Log::info('product->id=' . $item['product']->id);
            if ($item['product']->id === $id) {
                $item['quantity'] = $newQuantity;
                //\Log::info('quantity=' . $item['quantity']);
            
                break;
            }
        }
        return $array;
    }
}
