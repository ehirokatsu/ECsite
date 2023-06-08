<?php

namespace App\Listeners;

use App\Events\ContactSended;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

//メーラブルクラス使用
use App\Mail\ContactMail;

class SendConfirmMail
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
    public function handle(ContactSended $event): void
    {
        //メーラブルクラスで送信する
        \Mail::to($event->inputs['email'])->send(new ContactMail($event->inputs));
    }
}
