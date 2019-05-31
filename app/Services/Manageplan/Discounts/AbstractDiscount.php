<?php

namespace App\Services\Manageplan\Discounts;

Abstract Class AbstractDiscount
{   
    public $order;
    public $coupon;
    public $couponPrice = array();

    public function __construct($order, $coupon)
    {
        $this->order = $order;
        $this->coupon = $coupon;
    }

    public abstract function getTotalNonPercentageDiscount(bool $byProduct = false);

    public abstract function getTotalPercentageDiscount(bool $byProduct = false);
}
