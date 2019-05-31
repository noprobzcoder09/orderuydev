<?php

namespace App\Services\Customers\Account\Billing\Extended;

use App\Services\Orders\Session as SessionParent;

Class Session extends SessionParent
{     
    
    public $plan;
    public $planId;
    public $cycleId;
    public $vegetarianStatus;
    public $quantity;
    public $price;
    public $infusionsoftProductId;
    public $noMeals;
    public $noDays;
    public $single = false;
    public $subscriptionId; //Added new
    public $discountId; //Added new

    public function store(array $meals = []) 
    {   
        $data = $this->session->get();
        if (empty($data)) {
            $data = [];
        }

        if ($this->single) {
            $data = [];
        }
        
        foreach($meals as $key => $meal) {
            $data[$this->planId]['meals'][$key] = $meal;
        }
                
        if (!empty($this->plan)) {
            $data[$this->planId]['name'] = $this->plan;
        }

        if (!empty($this->planId)) {
            $data[$this->planId]['plan_id'] = $this->planId;
        }

        if (!empty($this->cycleId)) {
            $data[$this->planId]['cycle_id'] = $this->cycleId;
        }

        if (!empty($this->quantity)) {
            $data[$this->planId]['quantity'] = $this->quantity;
        }

        if (!empty($this->price)) {
            $data[$this->planId]['price'] = $this->price;
        }

        if (!empty($this->noDays)) {
            $data[$this->planId]['noDays'] = $this->noDays;
        }

        if (!empty($this->noMeals)) {
            $data[$this->planId]['noMeals'] = $this->noMeals;
        }

        if (!empty($this->infusionsoftProductId)) {
            $data[$this->planId]['infusion_product_id'] = $this->infusionsoftProductId;
        }

        if (!empty($this->subscriptionId)) {
            $data[$this->planId]['subscription_id'] = $this->subscriptionId;
        }

        if (!empty($this->discountId)) {
            $data[$this->planId]['discount_id'] = $this->discountId;
        }

        $data[$this->planId]['vegetarian'] = $this->vegetarianStatus;
        
        $this->session->store($data);
    }

    public function iHaveThisSubscription(int $subscriptionId)
    {   
        $data = $this->session->get();
        foreach($data as $row) {
            $row = (object)$row;
            if ($row->subscription_id == $subscriptionId) {
                return true;
            }
        }
        return false;
    }

   
}
