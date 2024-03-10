<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteUnnecessaryData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unnecessary-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete unnecessary data';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        //ファイルを削除
        $directoryPath = storage_path('app/' . \Config::get('filepath.imageTmpSaveFolder'));
        $files = \File::allFiles($directoryPath);
        foreach ($files as $file) {
            \File::delete($file->getPathname());
        }

        // ディレクトリ内の全てのサブフォルダを削除
        $directories = \File::directories($directoryPath);
        foreach ($directories as $dir) {
            \File::deleteDirectory($dir);
        }

    }
}
