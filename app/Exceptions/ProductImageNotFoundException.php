<?php

namespace App\Exceptions;

use Exception;

class ProductImageNotFoundException extends Exception
{
    protected $message = '商品画像が見つかりません';
}