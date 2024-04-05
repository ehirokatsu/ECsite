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
    public function __invoke($image, $fullPath, $imageFileName): void
    //public function test(): void
    {
        //
        //\Log::info('SaveImage test');
        $image->storeAs($fullPath, $imageFileName);
    }
}
