<?php

namespace App\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    protected $message;

    public function __construct()
    {
        // メッセージが指定されていない場合はデフォルトメッセージを設定
        parent::__construct($message ?? __('messages.product_not_found'));
    }
}
