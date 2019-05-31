<?php

namespace App\Services\Orders;

use App\Services\Orders\AbstractSessionOrder;

Class Session implements AbstractSessionOrder
{     
    
    public $plan;
    public $planId;
    public $cycleId;
    public $vegetarianStatus;
    public $quantity;
    public $price = 0;
    public $infusionsoftProductId;
    public $noMeals;
    public $noDays;
    public $single = false;

      
    public function __construct(\App\Services\Session\AdapterInterface $session)
    {
    	$this->session = $session;
    }

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

        if (!empty($this->cycleId)) {
        	$data[$this->planId]['cycle_id'] = $this->cycleId;
        }

        if (!empty($this->quantity)) {
        	$data[$this->planId]['quantity'] = $this->quantity;
        }

        $data[$this->planId]['price'] = $this->price;

        if (!empty($this->noDays)) {
            $data[$this->planId]['noDays'] = $this->noDays;
        }

        if (!empty($this->noMeals)) {
            $data[$this->planId]['noMeals'] = $this->noMeals;
        }

        if (!empty($this->infusionsoftProductId)) {
        	$data[$this->planId]['infusion_product_id'] = $this->infusionsoftProductId;
        }

        $data[$this->planId]['vegetarian'] = $this->vegetarianStatus;
        
        $this->session->store($data);
    }

    public function delete(int $planId)
    {
        $data = $this->session->get();
        unset($data[$planId]);
        $this->session->store($data);
    }

    public function destroy()
    {
        $this->session->destroy();
    }

    public function get()
    {
    	return $this->session->get();
    }

    public function getTotal()
    {
    	$total = array_reduce($this->get(), function($carry, $item) {
            $carry += $item['price'] * $item['quantity'];
            return $carry;
        });
        return $total;
    }

    public function getPlanId()
    {
    	$data = $this->session->get();
        if (empty($data)) {
            return 0;
        }

        foreach($data as $key => $row) {
            return $key;
        }
        return 0;
    }

    public function getPrice(int $planId)
    {
        $data = $this->session->get();
        if (empty($data)) {
            return 0;
        }

        return $data[$planId]['price'] ?? 0;
    }

    public function getMeals(int $planId)
    {
        $data = $this->session->get();
        if (empty($data)) {
            return [];
        }

        return $data[$planId]['meals'] ?? [];
    }

    public function isVegetarian(int $planId)
    {
        $data = $this->session->get();
        if (empty($data)) {
            return false;
        }

        return $data[$planId]['vegetarian'] ?? false;
    }
    
    public function getNoDays(int $planId)
    {
        $data = $this->session->get();
        if (empty($data)) {
            return 0;
        }

        return $data[$planId]['noDays'] ?? 0;
    }

    public function getNoMeals(int $planId)
    {
        $data = $this->session->get();
        if (empty($data)) {
            return 0;
        }

        return $data[$planId]['noMeals'] ?? 0;
    }
}
