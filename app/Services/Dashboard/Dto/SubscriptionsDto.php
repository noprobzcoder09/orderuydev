<?php

namespace App\Services\Dashboard\Dto;

use App\Services\Dashboard\Dto\ProductDto;

Class SubscriptionsDto
{   
    public function __construct(
        int $subscriptionId, 
        int $subscriptionCycleId, 
        ProductDto $product,
        int $discountId,
        array $coupons,
        string $status
    ) {

        $this->subscriptionId = $subscriptionId;
        $this->subscriptionCycleId = $subscriptionCycleId;
        $this->status = $status;
        $this->coupons = $coupons;
        $this->discountId = $discountId;
        $this->product = $product;
    }

    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    public function getSubscriptionCycleId()
    {
        return $this->subscriptionCycleId;
    }

    public function getCoupons()
    {
        return count($this->coupons) <= 0 ? [] : $this->coupons;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDiscountId()
    {
        return $this->discountId;
    }

    public function getProduct()
    {
        return $this->product;
    }

}
