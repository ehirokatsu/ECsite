<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    //カート、購入者用変数
    public $carts, $userInfos, $totalAmount;

    /**
     * Create a new event instance.
     */
    public function __construct($carts, $userInfos, $totalAmount)
    {
        //カート、購入者の内容を受け取る
        $this->carts = $carts;
        $this->userInfos = $userInfos;
        $this->totalAmount = $totalAmount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
