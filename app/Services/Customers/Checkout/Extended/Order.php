<?php

namespace App\Services\Customers\Checkout\Extended;

use App\Services\Orders\Factory as OrderFactory;
use App\Services\Session\Session as SessionStorage;
use App\Repository\ProductPlanRepository;

use \App\Services\Manageplan\Contracts\Order as OrderContract;
use \App\Services\Manageplan\Order as OrderParent;

Class Order extends OrderParent implements OrderContract
{   

    public function __construct()
    {
        $this->order = new OrderFactory;
        $this->order = $this->order->session(new SessionStorage('checkout-order'));
        $this->repo = new ProductPlanRepository;
    }

    public function store(int $planId, array $meals = [])
    {   
        $this->repo->setId($planId);
        $this->order->single = false;
        $this->order->planId = $planId;
        $this->order->quantity = 1;
        $this->order->price = $this->repo->getPrice();
        $this->order->plan = $this->repo->getPlanName();
        $this->order->infusionsoftProductId = $this->repo->getINFSProductId();
        $this->order->vegetarianStatus = $this->repo->isVegetarian() ? true : false;
        $this->order->noDays = $this->repo->getNoDays();
        $this->order->noMeals = $this->repo->getNoMeals();

        $this->order->store($meals);
    }

}
