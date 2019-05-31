<?php

namespace App\Services\Coupons\Validator;

use App\Services\Coupons\Validator\AbstractValidator;
use  App\Services\Coupons\Data;
use \App\Services\Manageplan\Contracts\Order as OrderContract;

Class Expired extends AbstractValidator
{    
    private $orders;

    public function __construct(string $code)
    {
        $this->data = new Data($code);
        $this->message = __('coupon.expired');
        
        $this->load();
    }

    public function load()
    {   
        if ($this->data->isExpired()) {
            return true;
        }
        return false;
    }
}
