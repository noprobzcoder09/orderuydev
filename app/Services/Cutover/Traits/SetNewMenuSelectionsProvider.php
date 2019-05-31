<?php

namespace App\Services\Cutover\Traits;

use App\Services\Cutover\Cycle\Selections\Get as GetMealsScheduled;
use App\Services\Cutover\Dto\MealPlans as MealPlansDto;

Trait SetNewMenuSelectionsProvider
{   
    private function setNewMenuSelectionsProvider()
    {   
        foreach($this->mealPlansRepository->getAll() as $row) 
        {
            $this->mealPlansDefaultMenu[$row->id] = $this->getDefaultMenu($row->id);
        }
    }

    private function getDefaultMenu(int $mealPlansId)
    {   
        $meals = $this->mealPlansRepository->get($mealPlansId);

        $meals = new MealPlansDto(
            $meals->id,
            $meals->no_days, 
            $meals->no_meals, 
            $meals->vegetarian
        );

        // Enable/Disable meals
        $this->enableDisableMealsStatus($this->currentCycleId);

        // Get default cycle selections
        $menu = $this->cycle->getDefaultMenu($this->currentCycleId, $meals->isVege());

        // Getting default meals for vego and non-vego
        // if ($meals->isVege()) {
        //     $default = $this->mealsRepository->getActiveMealsByActiveCycle($meals->isVege());  
        // } else {
        //     $default = $this->mealsRepository->getActiveMealsByActiveCycle();
        // }

        $menu = is_array($menu) ? $menu : json_decode($menu);
        $menu = empty($menu) ? [] : $menu;

        // $defaultMeals = array();
        // // Get meal ids
        // foreach($default as $row) {
        //     array_push($defaultMeals, $row->id);
        // }

        //$noMeals = $meals->getNoMeals();
        // If cycle meals default selections are lacking of meals 
        // base on the meal plans config
        // then retrieve some meals in the default active meals
        // $currentNoMenus = count($menu);
        // $currentNoMenusLeft = 0;
        // if ($currentNoMenus < $noMeals) {
        //     $currentNoMenusLeft = $noMeals - $currentNoMenus;
        // }
        
        // for($i = 0; $i < $currentNoMenusLeft; $i++) {
        //     if (!isset($defaultMeals[$i])) {
        //         $i = 0;
        //         $currentNoMenusLeft = $noMeals - count($menu);
        //     }
        //     if (! empty($defaultMeals)) {
        //         array_push($menu, $defaultMeals[$i]);
        //     }
        // }
        
        return json_encode($menu);
    }

    private function enableDisableMealsStatus(int $cycleId)
    {
        $getMealsScheduled = new GetMealsScheduled($cycleId);

        list($nonVego, $vego) = $getMealsScheduled->handle();

        //get default active vego and non-vego
        //non vego
        // $nonVego = array_map(function($meal) {
        //     return $meal->id;
        // }, $this->mealsRepository->getActiveNonVegetarian());
        

        // //vego
        // $vego = array_map(function($meal) {
        //     return $meal->id;
        // }, $this->mealsRepository->getActiveVegetarian());

        $nonVego = [];
        foreach ($this->mealsRepository->getActiveNonVegetarian() as $meal) {
            array_push($nonVego, $meal->id);
        }


        $vego = [];
        foreach ($this->mealsRepository->getActiveVegetarian() as $meal) {
            array_push($vego, $meal->id);
        }

        //dd($vego);
        
        //update default selections based from the active meals
        $this->cycle->updateDefaultMeals($cycleId, $nonVego, $vego);


    }
   
}
