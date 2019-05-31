<?php

namespace App\Services\Customers\Account\Billing\Extended;

use App\Services\Orders\Factory as OrderFactory;
use App\Services\Session\Session as SessionStorage;
use App\Services\Customers\Account\Billing\Extended\Session;
use App\Repository\ProductPlanRepository;

use \App\Services\Manageplan\Contracts\Order as OrderContract;
use \App\Services\Manageplan\Order as OrderParent;

Class Order extends OrderParent implements OrderContract
{   

    public $price;
    public $subscriptionId;
    public $discountId;

    public function __construct(string $sessionReference)
    {   
        $this->order = new OrderFactory;
        $this->order = new Session(new SessionStorage($sessionReference));
        $this->repo = new ProductPlanRepository;
    }

    public function store(int $planId, array $meals = [])
    {   
        $this->repo->setId($planId);
        
        $this->order->single = false;
        $this->order->subscriptionId = $this->subscriptionId;
        $this->order->discountId = $this->discountId;
        $this->order->planId = $planId;
        $this->order->quantity = 1;
        $this->order->price = $this->price;
        $this->order->plan = $this->repo->getPlanName();
        $this->order->infusionsoftProductId = $this->repo->getINFSProductId();
        $this->order->vegetarianStatus = $this->repo->isVegetarian() ? true : false;
        $this->order->noDays = $this->repo->getNoDays();
        $this->order->noMeals = $this->repo->getNoMeals();

        $this->order->store($meals);
    }

    public function iHaveThisSubscription(int $subscriptionId)
    {
        return $this->order->iHaveThisSubscription($subscriptionId);
    }

     public function setSubscriptionId($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    public function setDiscountId($discountId)
    {
        $this->discountId = $discountId;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

}
