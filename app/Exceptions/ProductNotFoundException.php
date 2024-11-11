<?php

namespace App\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    protected $message;

    public function __construct()
    {
        $this->message = __('messages.product_not_found');
    }
}
