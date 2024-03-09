<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MyLogger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:my-logger';

    /**
     * The console command description.
     *
     * @var string
     */
    //php artisan listで表示する時に使用される
    protected $description = 'Output one line of log.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        \Log::info('My Command test');
    }
}
