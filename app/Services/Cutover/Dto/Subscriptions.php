<?php

namespace App\Services\Cutover\Dto;

Class Subscriptions
{   
   public function __construct(int $subscriptionId, int $subscriptionCycleId, int $mealPlansId, string $status)
   {
        $this->subscriptionId = $subscriptionId;
        $this->subscriptionCycleId = $subscriptionCycleId;
        $this->status = $status;
        $this->mealPlansId = $mealPlansId;
   }

   public function getSubscriptionId()
   {
        return $this->subscriptionId;
   }

   public function getSubscriptionCycleId()
   {
        return $this->subscriptionCycleId;
   }

   public function getStatus()
   {
        return $this->status;
   }

   public function getMealPlansId()
   {
        return $this->mealPlansId;
   }

}
