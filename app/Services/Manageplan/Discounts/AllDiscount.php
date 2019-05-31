<?php

namespace App\Services\Manageplan\Discounts;

use App\Services\Manageplan\Discounts\AbstractDiscount;

Class AllDiscount extends AbstractDiscount
{   
    const fixed = 'fixed';
    const percent = 'percent';
   
    public function getTotalNonPercentageDiscount(bool $byProduct = false)
    {
        $discount = 0;
        foreach($this->coupon->get() as $row)
        {   
            if ($byProduct) {
                if (count($row['products']) > 0) {
                    if (strtolower($row['type']) == self::fixed) {
                        $discount += $row['discount'];
                    } 
                }
                continue;
            }

            if (count($row['products']) == 0) {
                if (strtolower($row['type']) == self::fixed) {
                    $discount += $row['discount'];
                } 
            }
        }
        return $discount;
    }

    public function getTotalPercentageDiscount(bool $byProduct = false)
    {
        $discount = 0;
        foreach($this->coupon->get() as $row)
        {   
            if ($byProduct) {
                if (count($row['products']) > 0) {
                    if (strtolower($row['type']) == self::percent) {
                        $discount += $row['discount']/100;
                    } 
                }
                continue;
            }

            if (count($row['products']) == 0) {
                if (strtolower($row['type']) == self::percent) {
                    $discount += $row['discount']/100;
                } 
            }
        }
        return $discount;
    }
}
