<?php

namespace App\Services\Cutover\Dto;

use App\Services\Cutover\Dto\Product as ProductDto;

Class SubscriptionsBilling
{   
   public function __construct($products, array $subscriptions, float $total)
   {
        $this->subscriptions = $subscriptions;
        $this->products = $products;
        $this->total = $total;
   }

   public function getSubscriptions()
   {
        return $this->subscriptions;
   }

   public function getProducts()
   {
        return $this->products;
   }

   public function getTotal()
   {
        return $this->total;
   }

}
