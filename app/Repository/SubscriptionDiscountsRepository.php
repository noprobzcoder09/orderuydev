<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\Subscriptions;
use App\Models\SubscriptionsDiscounts;
use App\Models\Coupons;
use Auth;
use App\Rules\Custom;

use DB;

Class SubscriptionDiscountsRepository
{   

    const primary_key = 'id';  
    public $id;

    public function __construct() 
    {
        $this->discounts = new SubscriptionsDiscounts;
    }

    public function store(float $totalDiscount, float $totalRecurDiscount, $meta_data)
    {   
        $status = $this->discounts->create([
            'total_discount' => $totalDiscount,
            'total_recur_discount' => $totalRecurDiscount,
            'meta_data'  => $meta_data
        ]);
        
        if(!empty($status->id)) {
            $meta_data = json_decode($meta_data);
            if (!empty($meta_data)) {
                foreach($meta_data as $row) {
                    // Set number used and flag USED if reach the max uses
                    (new \App\Services\Coupons\Model\Data)->setUsed($coupon_code->code);
                }
            }
        }

        $this->setId($status->id);
    }

    public function storeAsArray(array $data)
    {   
        $status = $this->discounts->create($data);
        
        if(!empty($status->id)) {
            $meta_data = json_decode($data['meta_data']);
            if (!empty($meta_data)) {
                foreach($meta_data as $row) {
                    // Set number used and flag USED if reach the max uses
                    (new \App\Services\Coupons\Model\Data)->setUsed($row->code);
                }
            }
        }

        $this->setId($status->id);
    }

    public function updateDiscountNumberSubscriptions(int $discountId, int $numberOfSubscriptions)
    {   
        $this->discounts->where('id', $discountId)
            ->update([
                'no_subscriptions' => $numberOfSubscriptions
            ]);
    }
    

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}

