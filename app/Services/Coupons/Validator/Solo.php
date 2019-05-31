<?php

namespace App\Services\Coupons\Validator;

use App\Services\Coupons\Validator\AbstractValidator;
use App\Services\Coupons\Model\Data;
use App\Services\Manageplan\Contracts\Coupon as CouponContract;

Class Solo extends AbstractValidator
{    
    private $coupon;
    private $order;

    public function __construct(string $code, CouponContract $coupon)
    {
        $this->data = new Data($code);
        $this->coupon = $coupon;

        $this->load();
    }

    public function load()
    {   
        if ($this->data->isSolo()) {
            if(count($this->coupon->get()) > 0) {
                $this->message = sprintf(__('coupon.solo'),$this->data->getCode());
                return $this->valid = false;
            }
        }

        foreach($this->coupon->get() as $row) 
        {
            $row = is_array($row) ? (object)$row : $row;
            if ($row->solo == 1) {
                $this->message = sprintf(__('coupon.solo'),$row->code);
                $this->valid = false;
                return;
            }
        }

        $this->valid = true;
        
    }

}
