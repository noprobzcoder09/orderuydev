<?php

namespace App\Services\Customers\Account\Billing\Dto;

use App\Services\Customers\Account\Billing\Dto\SubscriptionsCycleStatusDto;

Class SubscriptionsSelectionsDto
{   
   public function __construct(
        SubscriptionsCycleStatusDto $status,
        int $id, 
        int $cycleId, 
        int $discountId,
        array $coupons
    ) {
        $this->id = $id;
        $this->cycleId = $cycleId;
        $this->status = $status;
        $this->discountId = $discountId;
        $this->coupons = $coupons;
   }

   public function getId()
   {
        return $this->id;
   }

   public function getCycleId()
   {
        return $this->cycleId;
   }

   public function getDiscountId()
   {
        return $this->discountId;
   }

   public function getStatus()
   {
        return $this->status;
   }

   public function getCoupons()
   {
        return count($this->coupons) <= 0 ? [] : $this->coupons;
   }

   public function isEmpty()
   {
        return empty($this->id);
   }

}
