<?php

namespace App\UseCases\Image;

class SaveImage
{
    /*
    public function __construct(HelloService $helloService)//use必須
    {
        $this->helloService = $helloService;
    }
*/
    public function __invoke(): void
    //public function test(): void
    {
        //
        \Log::info('SaveImage test');
    }
}
