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
            # /opt/homebrew/bin/phpは、which phpで探した
            /opt/homebrew/bin/php ?
            /Users/hiro/ECsite/artisan ?
            * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
         */
        $schedule->call(function () {
            \Log::info('cron test');
        })->everySecond();
        
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
