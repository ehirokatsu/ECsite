<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    //Orderモデル。最初のOは小文字にする
    public function order()
    {
        //こちらは1Order.phpと同じくOは大文字にする
        return $this->belongsTo(Order::class);
    }
}
