<?php

namespace App\Services\Customers\Checkout;

use App\Services\Customers\Checkout\Extended\Order;
use App\Repository\CycleRepository;
use App\Repository\MealsRepository;
use App\Repository\ProductPlanRepository;
use  App\Repository\UsersRepository;

class Client extends Order
{      
    private $id;
    public function __construct(string $sku)
    {   
        parent::__construct();
        $this->setSku($sku);
        
        $this->mealRepository = new MealsRepository;
        $this->cycleRepository = new CycleRepository;
        $this->usersRepository = new UsersRepository;
    }

    public function isDinnerOnly(): bool
    {   
        $meals = $this->repo->getNoMeals();
        $days = $this->repo->getNoDays();
        if (empty($meals)) {
            return true;
        }

        return $meals/$days == 1;
    }

    public function getPlan()
    {   
       return $this->repo->get($this->id);
    }

    public function isExisting()
    {   
       return empty($this->repo->get($this->id));
    }

    public function getDefaultSelection()
    {   
       return $this->cycleRepository->getDefaultSelections(
            $this->cycleRepository->getActiveId()
        );
    }

    public function getVegDefaultSelection()
    {   
       return $this->cycleRepository->getVegDefaultSelections(
            $this->cycleRepository->getActiveId()
        );
    }


    public function getMenus(): array
    {    
        $response = [];

        $noDays = $this->repo->getNoDays();
        $noMeals = $this->repo->getNoMeals();
        $isVegetarian = $this->repo->isVegetarian();
        $isDinnerOnly = $this->isDinnerOnly();
        $defaultMeals = [];

        
        
//dd(auth()->user()->id);
        if (auth()->check()) {
            $deliveryTimingId = $this->usersRepository->getUserDeliveryTimingId(auth()->user()->id);           
            $defaultMeals = $this->cycleRepository->getActiveByTimingId($deliveryTimingId);

            if (empty($deliveryTimingId)) {
                $defaultMeals = $this->cycleRepository->getFirstActiveCycle();
            }

        } else {
            $defaultMeals = $this->cycleRepository->getFirstActiveCycle();
        }


        if ($isVegetarian) {
            $default = $this->mealRepository->getActiveVegetarian();
            $mealsVego = json_decode($defaultMeals->default_selections_veg);   
        } else {
            $default = $this->mealRepository->getActiveMealsWithTopNonVego();
            $mealsNonVego = json_decode($defaultMeals->default_selections);   
        }

// echo '<pre>';
// print_r($default);
// echo '</pre>';
// exit;
        
        
        // //get default active vego and non-vego
        // //non vego
        // $nonVego = array_map(function($meal) {
        //     return $meal->id;
        // }, $this->cycleRepository->getActiveNonVegetarian());

        // //vego
        // $vego = array_map(function($meal) {
        //     return $meal->id;
        // }, $this->cycleRepository->getActiveVegetarian());
        

            
        // List all meals in put input selection
        $response['dinner'] = $default;
        $response['lunch'] =  $isDinnerOnly ? [] : $default;

        // Get meal ids
        $defaultAllMeals = array();
        foreach($default as $row) {
            array_push($defaultAllMeals, $row->id);
        }

        // If it has selected selection before then show
        // otherwise get a new meals selection
        if (!empty($this->get())) {
            $meals = $this->getMeals($this->id);
        } 
        else {            
            //$meals = (!$isVegetarian) ? $this->getDefaultSelection() : $this->getVegDefaultSelection();
            $meals = (!$isVegetarian) ? $mealsNonVego : $mealsVego;
            $meals = is_array($meals) ? $meals   :   json_decode($meals);
        }

        //dd($meals);

        // Determine the number of meals by no days to be stored
        // If lack of meals for dinner/lunch
        // then set a default meal from default meals
        // To ensure that there is no empty default selections
        $meals = is_null($meals) ? [] : $meals;
        $currentNoMenus = count($meals);
        $currentNoMenusLeft = 0;
        if ($currentNoMenus < $noMeals) {
            $currentNoMenusLeft = $noMeals - $currentNoMenus;
        }
        

        for($i = 0; $i < $currentNoMenusLeft; $i++) {
            if (!isset($defaultAllMeals[$i])) {
                $currentNoMenusLeft = $i;
                $i = 0;
            }
            array_push($meals, $defaultAllMeals[$i]);
        }

        $mealsLunch = $meals;
        $mealsDinner = $meals;
        $dinner = $meals;

        $lunch = !$isDinnerOnly ? array_splice($mealsLunch,0, $noDays) : [];
        $dinner = !$isDinnerOnly ? array_splice($mealsDinner, $noDays, $noMeals) : $dinner;
        
        $response['default']['dinner'] = $dinner;
        $response['default']['lunch'] = $lunch;    

        $response['meals'] = $default;

        $response['noMeals'] = $noMeals;
        $response['noDays'] = $noDays;
        $response['price'] = $this->repo->getPrice();
        $response['isDinnerOnly'] = $this->isDinnerOnly();
//dd($response);
        return $response;
    }

    public function setSku(string $sku)
    {
        $this->sku = trim(str_replace(' ', '',$sku));
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getPlanIdBySku()
    {
        return $this->repo->getIdBySku($this->getSku());
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

     public function getId()
    {
        return $this->id;
    }

    public function setPlan(int $planId)
    {
        $this->repo->setId($planId);
        $this->setId($planId);
    }
}


