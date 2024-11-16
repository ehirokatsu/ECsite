<?php

namespace App\Exceptions;

use Exception;

class ProductImageNotFoundException extends Exception
{
    protected $message;

    public function __construct($message = null)
    {
        //$this->message = __('messages.product_image_not_found');
        parent::__construct($message ?? __('messages.product_image_not_found'));
    }
}