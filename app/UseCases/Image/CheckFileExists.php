<?php

namespace App\UseCases\Image;

class CheckFileExists
{

    public function __invoke($path): bool
    //public function test(): void
    {
        //
        if (\File::exists($path) && !is_dir($path)) {
            return true;
        } else {
            return false;
        }
    }
}
