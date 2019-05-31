<?php

namespace App\Services\Manageplan;

use App\Services\Orders\Factory as OrderFactory;
use App\Services\Session\Session as SessionStorage;
use App\Repository\ProductPlanRepository;

Class Order implements \App\Services\Manageplan\Contracts\Order
{   

    public function __construct()
    {
        $this->order = new OrderFactory;
        $this->order = $this->order->session(new SessionStorage('manage-plan-order'));
        $this->repo = new ProductPlanRepository;
    }

    public function store(int $planId, array $meals = [])
    {	
        $this->repo->setId($planId);
        $this->order->single = true;
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

    public function get()
    {
        return $this->order->get();
    }

    public function getPlanId()
    {
        return $this->order->getPlanId();
    }

    public function getPrice(int $planId)
    {
        return $this->order->getPrice($planId);
    }

    public function getMeals(int $planId)
    {
        return $this->order->getMeals($planId);
    }

    public function isVegetarian(int $planId)
    {
        return $this->order->isVegetarian($planId);
    }

    public function getNoDays(int $planId)
    {
        return $this->order->getNoDays($planId);
    }

    public function getNoMeals(int $planId)
    {
        return $this->order->getNoMeals($planId);
    }

    public function total()
    {
        return $this->order->getTotal();
    }

    public function delete(int $planId)
    {
        return $this->order->delete($planId);
    }

    public function destroy()
    {
        return $this->order->destroy();
    }

}
