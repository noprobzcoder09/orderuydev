<?php

namespace App\Services\Manageplan;

Class WorkerOld
{   
    const fixed = 'fixed';
    const percent = 'percent';
   
    public function __construct($order, $coupon)
    {
    	$this->order = $order;
    	$this->coupon = $coupon;
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
        $total = $this->total();
        return  $total - $this->getTotalRecurringDiscount();
    }

    public function getTotalDiscount() 
    {   
        $d1 = $this->getTotalSpecifiedProductDiscounts();
        $d2 = $this->getTotalNonSpecifiedProductDiscounts();
        
        $totalDiscount = $d1+$d2;

        return $totalDiscount;        
    }

    public function getTotalRecurringDiscount() 
    {   
        return $this->getTotalNoneSpecifiedRecurringDiscount() + $this->getTotalSpecifiedRecurringDiscount();
    }

    public function getTotalNoneSpecifiedRecurringDiscount()
    {
        // Get Recurring discounts
        $recurrDiscounts = array_reduce($this->getTotalNoneSpecifiedRecurringDiscountList(), function($carry, $item) {
                $carry += $item;
                return $carry;
        }, 0);

        return $recurrDiscounts;
    }

    public function getTotalSpecifiedRecurringDiscount()
    {
        // Get Recurring discounts
        $recurrDiscounts = array_reduce($this->getTotalSpecifiedRecurringDiscountList(), function($carry, $item) {
                $carry += $item;
                return $carry;
        }, 0);

        return $recurrDiscounts;
    }

    private function getTotalSpecifiedProductDiscounts()
    {        
        // Get total with Less discount for each specicied product promo
        $individualTotal = array_reduce($this->getSpecifiedProductDiscountsList(), function($carry, $item) {
            $carry += $item;
            return $carry;
        }, 0);

        return $individualTotal;
    }

    private function getTotalNonSpecifiedProductDiscounts()
    {
        // Get Total discounts for no product associated
        $NonProductTotalDiscounts = array_reduce($this->getNonSpecifiedProductDiscountsList(), function($carry, $item) {
            $carry += $item;
            return $carry;
        }, 0);

        return $NonProductTotalDiscounts;
    }

    
  
    private function getTotalNoneSpecifiedRecurringDiscountList(): array
    {
        $discount = [];
        foreach($this->order->get() as $key => $row) 
        {
            $coupons = $this->coupon->get();
            if (empty($coupons)) continue;
            foreach($coupons as $coupon) 
            {       
                if (!in_array($key, $coupon['products'])) 
                {
                    if ($coupon['recur'] == 1) 
                    {
                        if (strtolower($coupon['type']) == self::fixed) {
                            $discount[] = $coupon['discount'];
                        } elseif (strtolower($coupon['type']) == self::percent) {
                            $discount[] = $row['price'] * ($coupon['discount']/100);
                        }
                    }
                }
            }
        }

        return $discount;
    }

    private function getTotalSpecifiedRecurringDiscountList(): array
    {
        $discount = [];
        foreach($this->order->get() as $key => $row) 
        {
            $coupons = $this->coupon->get();
            if (empty($coupons)) continue;
            foreach($coupons as $coupon) 
            {       
                if (in_array($key, $coupon['products'])) 
                {
                    if ($coupon['recur'] == 1) 
                    {
                        if (strtolower($coupon['type']) == self::fixed) {
                            $discount[] = $coupon['discount'];
                        } elseif (strtolower($coupon['type']) == self::percent) {
                            $discount[] = $row['price'] * ($coupon['discount']/100);
                        }
                    }
                }
            }
        }

        return $discount;
    }

    private function getNonSpecifiedProductDiscountsList(): array
    {
        $discounts = [];
        foreach($this->order->get() as $key => $row) 
        {
            $coupons = $this->coupon->get();
            if (empty($coupons)) continue;
            foreach($coupons as $coupon) 
            {
                if (empty($coupon['products'])) 
                {   
                    if (!in_array($key, $coupon['products'])) 
                    {
                        if (strtolower($coupon['type']) == self::fixed) {
                            $discounts[] = $coupon['discount'];
                        } elseif (strtolower($coupon['type']) == self::percent) {
                            $discounts[] = $row['price'] * ($coupon['discount']/100);
                        }
                    }
                }
            }
        }
        
        return $discounts;
    }

    private function getSpecifiedProductDiscountsList(): array
    {
        $discounts = [];
        
        foreach($this->order->get() as $key => $row) 
        {
            $coupons = $this->coupon->get();
            if (empty($coupons)) continue;
            foreach($coupons as $coupon) 
            {
                // Get specific product coupons
                if (count($coupon['products']) > 0) 
                {
                    if (in_array($key, $coupon['products'])) 
                    {
                        if (strtolower($coupon['type']) == self::fixed) {
                            $discounts[] = $coupon['discount'];
                        } elseif (strtolower($coupon['type']) == self::percent) {
                            $discounts[] = $row['price'] * $coupon['discount']/100;
                        }
                    }
                }
                // Get specific user coupons
                // --->
            }
        }
        
        return $discounts;
    }
}
