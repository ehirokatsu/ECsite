<?php

namespace App\UseCases\Cart;

class RemoveCartItemsById
{

    public function __invoke($array, $id)
    {

        /*
        array_filter()の1つ目の引数はフィルタリングする配列で、2つ目の引数は
        コールバック関数です。配列の各要素に対して実行され、その要素が新しい配列に含まれるべきかどうかを判断する。
        よって、$item = [
            'product' => $product,
            'quantity' => 1,
            ]
        となる。$item['product']でProductインスタンスにアクセスし、->idでID要素を参照する
        */

        $tmp = array_filter($array, function($item) use ($id) {
            return $item['product']->id != $id;
        });

        //カート配列の添え字を０オリジンにする
        $tmp = array_values($tmp);

        return $tmp;
    }
}
