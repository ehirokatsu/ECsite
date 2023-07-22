<?php

namespace App\Listeners;    

use App\Events\OrderCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;

class RegisterOrderDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCompleted $event): void
    {
        //
        \Log::info('RegisterOrder');

        //カートの内容をイベントから受け取る
        $carts = $event->carts;

        //購入者情報をイベントから取得する
        $userInfos = $event->userInfos;

        //空の注文レコードを作成
        $order = new Order;

        //データを登録
        $order->ordered_on = Carbon::now()->format('Y_m_d_H_i_s');

        //ログイン購入時のみユーザーIDを格納する
        if (!empty($userInfos['id'])) {
            $order->user_id = $userInfos['id'];
        }
        
        $order->name = $userInfos['name'];
        $order->email = $userInfos['email'];
        $order->postal_code = $userInfos['postalCode'];
        $order->address_1 = $userInfos['address1'];
        $order->address_2 = $userInfos['address2'];
        $order->address_3 = $userInfos['address3'];
        $order->phone_number = $userInfos['phoneNumber'];

        $order->save();

        

        //現在のカート内容を取得
        foreach ($carts as $cart) {
            //\Log::info($cart['product']->name);

            //空の注文詳細レコードを作成
            $orderDetail = new OrderDetail;

            //データを登録
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $cart['product']->id;
            $orderDetail->name = $cart['product']->name;
            $orderDetail->cost = $cart['product']->cost;
            $orderDetail->quantity = $cart['quantity'];

            $orderDetail->save();

        }
    }
}
