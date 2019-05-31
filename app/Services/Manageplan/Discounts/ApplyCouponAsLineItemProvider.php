<?php

namespace App\Services\Manageplan\Discounts;

Trait ApplyCouponAsLineItemProvider
{   
    public function applyCouponAsLineItemProvider(array &$products)
    {
        $itemType = (int)env('PRODUCT_ITEMTYPE');
        $couponType = env('COUPON_ITEMTYPE');
        
        foreach($this->getDiscountWithCalculatedPrice() as $row) {
            $row = is_array($row) ? (object)$row : $row;
            $products[] = [
                'infusionsoftProductId' => 0, 
                'itemType' => $itemType, 
                'price' => -$row->total, 
                'quantity' => 1, 
                'description' => 'Coupon: '. $row->code, 
                'notes' => 'Coupon: '. $row->discountValue. ' '.$row->code.' '.$row->name,
                'productType' => $couponType
            ];
        }
    }

    public function applyRecurringCouponAsLineItemProvider(array &$products)
    {   
        $itemType = (int)env('PRODUCT_ITEMTYPE');
        $couponType = env('COUPON_ITEMTYPE');
        foreach($this->getRecurringDiscountWithCalculatedPrice() as $row) {
            $row = is_array($row) ? (object)$row : $row;
            $products[] = [
                'infusionsoftProductId' => 0, 
                'itemType' => $itemType, 
                'price' => -$row->total, 
                'quantity' => 1, 
                'description' => 'Coupon: '. $row->code, 
                'notes' => 'Coupon: '. $row->discountValue. ' '.$row->code.' '.$row->name,
                'productType' => $couponType
            ];
        }
    }
}
