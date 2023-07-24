<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderDetail;


class OrderHistoryController extends Controller
{
    //
    public function index()
    {
        $user = \Auth::user();

        $orders = Order::where('user_id', '=' ,$user->id)->get();
        //dump($orders);
        $param = ['orders' => $orders];
        return view('orderHistory.index', $param);
    }

    public function show(string $id)
    {
        //$orderDetails = Order::where('user_id', '=' ,1)->get();
        $orderDetails = OrderDetail::with('order')->with('product')->where('order_id', '=' ,$id)->get();
        $order = Order::where('id', '=' ,$id)->first();
        //$orderDetails = OrderDetail::where('order_id', '=' ,$id)->get();
        //$orderDetails = OrderDetail::find($id);
        //dd($order);
        $param = [
            'orderDetails' => $orderDetails,
            'order' => $order,
        ];
        return view('orderHistory.show', $param);
    }
}
