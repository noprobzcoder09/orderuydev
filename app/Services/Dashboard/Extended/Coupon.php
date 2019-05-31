<?php

namespace App\Services\Dashboard\Extended;

use App\Services\Coupons\Factory as CouponFactory;
use App\Services\Session\Session as SessionStorage;
use App\Repository\CouponsRepository;
use App\Services\Manageplan\Contracts\Coupon as CouponInterface;

use  App\Services\Manageplan\Coupon as CouponParent;

Class Coupon extends CouponParent implements CouponInterface
{   

    public function __construct()
    {
        $this->coupon = new CouponFactory;
        $this->coupon = $this->coupon->session(new SessionStorage('coupon-manageplan-display'));
        $this->repo = new CouponsRepository;
    }

    public function store(string $code)
    {
        $coupon = $this->repo->getByCode($code);
        $this->coupon->store([
            'code'      => $coupon->coupon_code,
            'type'      => $coupon->discount_type,
            'discount'  => $coupon->discount_value,
            'products'  => json_decode($coupon->products),
            'users'  => json_decode($coupon->users),
            'onetime'  => $coupon->onetime,
            'solo'  => $coupon->solo,
            'recur'  => $coupon->recur,
            'isfixed' => strtolower($coupon->discount_type) == 'fixed'
        ]);
    }

    public function setDiscountId($discountId)
    {
        $this->discountId = $discountId;
    }

    public function setSubscriptionId($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }
}
