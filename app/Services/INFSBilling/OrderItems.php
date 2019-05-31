<?php

namespace App\Services\INFSBilling;

Class OrderItems extends \App\Services\InfusionSoftServices
{   
    
   public function __construct(int $orderId, array $products, $api = null)
   {     
        if (is_null($api)) {
            parent::__construct();
        } else {
            $this->api = $api;
        }
        
        $couponType = strtolower(env('COUPON_ITEMTYPE'));
        $BILLING_SANBOX = env('BILLING_SANBOX', false);
        // Iterate items and create order items
        foreach($products as $row) 
        {   
            $row = is_array($row) ? (object)$row : $row;
            $productType = strtolower($row->productType ?? '');

            $price = $row->price;
            if ($BILLING_SANBOX) {
                $price = ($productType == $couponType) ? -0.01 : 0.01;
            }
            // insert items
            $id = $this->addOrderItem(
                $orderId, 
                $row->infusionsoftProductId,
                $row->itemType, 
                $price, 
                $row->quantity, 
                $row->description, 
                $row->notes
            );

            if (empty($id)) {
                throw new \Exception(sprintf(__('crud.failedToCreate'),'Infusionsoft order item '.$row->description), 1);
                
            }
        }
   }
}
    
