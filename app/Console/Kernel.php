<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        /**
          *  cronに下記を登録する。
            # crontab -eに記載する内容。
            # crontab -lで内容確認。
            # crontab -rで削除。動作確認したら削除すること。
            
            * * * * * php /Users/neko/ECsite/artisan schedule:run >> /dev/null 2>&1
            →動作せず
            * * * * * cd /Users/neko/ECsite && php artisan schedule:run >> /dev/null 2>&1
            →動作せず
            原因不明なので諦める。動作確認は、php artisan schedule:workで確認する。
         */
        /*
        $schedule->call(function () {
            \Log::info('cron test');
        })->everyMinute();
        */

        //タスクスケジュールで実行する処理はコマンド化するのが一般的。
        $schedule->command('app:delete-unnecessary-data')
        ->dailyAt('2:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
