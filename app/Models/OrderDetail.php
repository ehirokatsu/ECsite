<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//戻り値の型指定で使用
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    //Order.phpモデル。最初のOは小文字にする
    public function order(): BelongsTo
    {
        //こちらはOrder.phpと同じくOは大文字にする
        //order_detailsテーブルにある外部キーをorder_idと判断する
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
