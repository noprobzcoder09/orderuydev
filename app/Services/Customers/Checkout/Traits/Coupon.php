<?php

namespace App\Services\Customers\Checkout\Traits;

use App\Services\Coupons\Validator\Factory;
use \App\Services\Validator;
use Auth;

Trait Coupon
{   

    public function removeCoupon()
    {   
        $this->coupon->delete($this->request->getPromoCode());
    }
    
    public function storeCoupon()
    {
        try 
        {
            $this->validateCoupons();
            $this->coupon->store($this->request->getPromoCode());

            return ['success' => true];
        }
        catch (\Exception $e)
        {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function validateCoupons()
    {
        $factory = new Factory(Auth::check() ? Auth::id() : 0);

        $this->validator = new Validator;
        
        $this->validator->validate([
            'coupon_code' => $this->request->getPromoCode()
        ], [
            'coupon_code' => [$factory->checkout()]
        ]);
        
        if (!$this->validator->isValid()) {
            throw new \Exception($this->validator->filterError($this->validator->getMessage()), 1);
        }
    }
}


