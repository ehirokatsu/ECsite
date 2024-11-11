<?php

namespace App\Exceptions;

use Exception;

class ProductImageNotFoundException extends Exception
{
    protected $message;

    public function __construct()
    {
        $this->message = __('messages.product_image_not_found');
    }
}