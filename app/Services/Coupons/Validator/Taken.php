<?php

namespace App\Services\Coupons\Validator;

use App\Services\Coupons\Validator\AbstractValidator;
use App\Services\Coupons\Model\Data;
use App\Services\Manageplan\Contracts\Coupon as CouponContract;

Class Taken extends AbstractValidator
{    
    private $coupon;

    public function __construct(string $code, CouponContract $coupon)
    {
        $this->data = new Data($code);
        $this->coupon = $coupon;
        $this->message = sprintf(__('coupon.code_exist'),$code);
        
        $this->load();
    }

    public function load()
    {
        foreach($this->coupon->get() as $row)
        {   
            $row = is_array($row) ? (object)$row : $row;
            if ($row->code == $this->data->getCode()) {
                $this->valid = false;
                return;
            }
        }
        $this->valid = true;
    }

}
