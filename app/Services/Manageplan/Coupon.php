<?php

namespace App\Services\Manageplan;

use App\Services\Coupons\Factory as CouponFactory;
use App\Services\Session\Session as SessionStorage;
use App\Repository\CouponsRepository;
use App\Services\Manageplan;

Class Coupon  implements \App\Services\Manageplan\Contracts\Coupon
{   

    public function __construct()
    {
        $this->coupon = new CouponFactory;
        $this->coupon = $this->coupon->session(new SessionStorage('manage-plan-coupon'));
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

    public function get()
    {
        return $this->coupon->get();
    }

    public function delete(string $code)
    {
        return $this->coupon->delete($code);
    }

    public function destroy()
    {
        return $this->coupon->destroy();
    }

}
