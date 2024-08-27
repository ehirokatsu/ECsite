<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Mail\OrderMail;

class SendOrderMail
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

        $totalAmount = 0;

        foreach ($event->carts as $cart) {
            $totalAmount = $totalAmount + (int)($cart['product']->cost) * (int)($cart['quantity']);
        }
        //
        \Mail::to($event->userInfos['email'])->send(new OrderMail($event->carts, $event->userInfos, $totalAmount));
    }
}
