<?php

namespace App\Listeners;

use App\Events\ContactSended;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendConfirmMail2
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
        //クロージャに引き渡すので一旦変数に入れる
        $inputs = $event->inputs;

        //Mailクラスのみ使用する場合。内容はSendMailクラスと同じ
        //クロージャ内で$inputsを使うのでuseを使う。
        \Mail::send('contact.mail', $inputs, function ($message) use ($inputs) {
            $message->to($inputs['email'])
            ->from('src@example.com')
            ->subject($inputs['title']);
        });

        /*
        //Mailクラスでテキストメールを送信する場合
        \Mail::send(['text' => 'contact.mail'], $event->inputs, function ($message) use ($inputs) {
            $message->to($inputs['email'])
            ->from('src@example.com')
            ->subject($inputs['title']);
        });
        */
        
    }
}
