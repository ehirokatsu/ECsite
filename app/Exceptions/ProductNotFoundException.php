<?php

namespace App\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    protected $message = '指定された商品が見つかりません';
}
