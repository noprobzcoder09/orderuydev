<?php

namespace App\Services\Customers\Account\Billing\Dto;

use App\Services\Coupons\Validator\Factory as CouponFactory;
use \App\Services\Validator;

use App\Services\Customers\Account\Billing\Dto\CouponDto;

Class UnpaidCouponDto extends CouponDto
{   
    public function parse()
    {
        $i = 0;
        foreach($this->coupons as $coupon) 
        {
            $coupon = (object)$coupon;
            if ($coupon->recur == '1') 
            {
                $factory = new CouponFactory($this->userId);
                $this->validator = new Validator;
                $this->validator->validate(['coupon_code' => $coupon->code], [
                    'coupon_code' => [$factory->unpaidBilling()]
                ]);
                
                if ($this->validator->isValid()) {
                    $i++;
                    array_push($this->couponData, $coupon->code);
                }
            }
        }
    }

}
