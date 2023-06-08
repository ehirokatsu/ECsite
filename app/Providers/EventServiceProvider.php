<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

//追加
use App\Events\ContactSended;
use App\Listeners\SendConfirmMail;
use App\Listeners\SendConfirmMail2;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        //イベントクラスとリスナークラスを関連づけ
        ContactSended::class => [
            SendConfirmMail::class,
        ],
        /*メールクラス用リスナー。使用する時にコメントを外す
        ContactSended::class => [
            SendConfirmMail2::class,
        ],
        */
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
