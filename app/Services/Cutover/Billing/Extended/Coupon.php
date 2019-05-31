<?php

namespace App\Services\Cutover\Billing\Extended;

use App\Services\Coupons\Factory as CouponFactory;
use App\Services\Session\Session as SessionStorage;
use App\Repository\CouponsRepository;
use App\Services\Manageplan;
use App\Services\Manageplan\Contracts\Coupon as CouponInterface;

use  App\Services\Manageplan\Coupon as CouponParent;

Class Coupon extends CouponParent implements CouponInterface
{   

    public function __construct()
    {
        $this->coupon = new CouponFactory;
        $this->coupon = $this->coupon->session(new SessionStorage('recurring-order-coupon'));
        $this->repo = new CouponsRepository;
    }

}
