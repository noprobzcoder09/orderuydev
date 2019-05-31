<?php

namespace App\Services\Manageplan;

use App\Services\Manageplan\Discounts\GetInvidualDiscountPrice;
use App\Services\Manageplan\Discounts\ApplyCouponAsLineItemProvider;

Class Worker
{   
    use ApplyCouponAsLineItemProvider;

    const fixed = 'fixed';
    const percent = 'percent';
   
    public function __construct($order, $coupon)
    {
    	$this->order = $order;
    	$this->coupon = $coupon;

        $this->couponPrice = new GetInvidualDiscountPrice($order, $coupon);
    }

    public function getGrandTotal()
    {
        return $this->total();
    }

    public function total()
    {
        return $this->order->total();
    }

    public function getTotalThisWeek()
    {
        return $this->total() - $this->getTotalDiscount();
    }

    public function getTotalAfterThisWeek()
    {   
        return $this->total() - $this->getTotalRecurringDiscount();
    }

    public function getTotalDiscount() 
    {   
        return $this->couponPrice->getTotalDiscount();
    }

    public function getTotalRecurringDiscount() 
    {   
        return $this->couponPrice->getTotalRecurring();
    }

    public function getDiscountWithCalculatedPrice()
    {
        return $this->couponPrice->getAll();
    }

     public function getRecurringDiscountWithCalculatedPrice()
    {
        return $this->couponPrice->getRecurring();
    }

}
