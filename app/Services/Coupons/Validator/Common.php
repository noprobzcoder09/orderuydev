<?php

namespace App\Services\Coupons\Validator;

use App\Services\Coupons\Validator\AbstractValidator;
use App\Services\Coupons\Validator\Data;
use App\Services\Coupons\Model\Data as Model;

Class Common extends AbstractValidator
{    
    public function __construct(string $code)
    {
        $this->data = new Data($code);
        $this->model = new Model($code);
        
        $this->load();
    }

    public function load()
    {   
        if (!$this->model->find())
        {
            $this->message = __('coupon.not_found');
            return $this->valid = false;
        }

        if ($this->model->isUsed())
        {
            $this->message = __('coupon.used');
            return $this->valid = false;
        }

        if ($this->data->isExpired())
        {
            $this->message = __('coupon.expired');
            return $this->valid = false;
        }

        $this->valid = true;
    }

}
