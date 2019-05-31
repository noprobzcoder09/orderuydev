<?php

namespace App\Services\Coupons\Validator;

use  App\Services\Coupons\Validator\Data;
use  App\Services\Coupons\Model\Data as Model;

Abstract Class AbstractValidator
{    
    public $valid = false;
    public $message;
    
    abstract public function load();

    public function valid() 
    {
        return $this->valid;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
