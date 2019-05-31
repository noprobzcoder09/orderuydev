<?php

namespace App\Services\INFSBilling\Validator;

Class OrderItems
{   

    private static $importantFields = ['infusionsoftProductId', 'itemType', 'price', 'quantity', 'description', 'notes'];
    private static $allowedEmpty = ['notes'];

    public function validate(array $products)
    {   
        $couponType = env('COUPON_ITEMTYPE');
        $productRow = is_array($products) ? (object)$products : $products;
        $productType = $productRow->productType ?? '';

        if ($productType == $couponType) {
            unset(static::$importantFields[0]);
        }
        
        foreach(static::$importantFields as $key) {
            if (!in_array($key, array_keys($products))) {
                $important[] = $key;
                continue;
            }

            if (empty($products[$key]) && !in_array($key, static::$allowedEmpty)) {
                $important[] = $key;
                continue;
            }
        }

        if (!empty($important)) {
            throw new \Exception(__('Order products Required fields are empty such as '.implode(', ', $important)), 1);
        }
    }
}

