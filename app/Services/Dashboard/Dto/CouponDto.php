<?php

namespace App\Services\Dashboard\Dto;

use App\Services\Coupons\Validator\Factory as CouponFactory;
use App\Services\Validator;

Class CouponDto
{   
    private $couponData = array();
    public function __construct(int $userId, array $coupons)
    {
        $this->userId = $userId;
        $this->coupons = $coupons;
    }   

    public function get()
    {
        $this->parse();
        return $this->couponData;
    }

    public function parse()
    {
        foreach($this->coupons as $coupon) 
        {
            $coupon = (object)$coupon;
            if ($coupon->recur == '1') 
            {
                $factory = new CouponFactory($this->userId);
                $this->validator = new Validator;
                $this->validator->validate(['coupon_code' => $coupon->code], [
                    'coupon_code' => [$factory->totalSharedDiscount()]
                ]);
                if ($this->validator->isValid()) {
                    array_push($this->couponData, $coupon->code);
                }
            }
        }
    }

}
