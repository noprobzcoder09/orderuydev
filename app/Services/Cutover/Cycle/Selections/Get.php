<?php

namespace App\Services\Cutover\Cycle\Selections;

use App\Services\Cutover\Data\MealStatus;
use App\Services\Cutover\Data\Meals;
use App\Repository\MealsRepository;

Class Get
{   
    public function __construct(int $cycleId)
    {
        $this->mealStatus = new MealStatus;
        $this->meals = new Meals;
        $this->cycleId = $cycleId;
        $this->mealsRepository = new MealsRepository;
    }

    public function handle()
    {
        $meals = [];
        $meals_veg = [];
        $meals_ids_to_removed = [];
        foreach($this->mealStatus->get($this->cycleId) as $row) {  
            $meal_ids_remove = json_decode($row->meal_ids_remove);
            $meal_ids_add = json_decode($row->meal_ids_add);

            if(!empty($meal_ids_remove)) {
                $meals_ids_to_removed = $meal_ids_remove;
            }

            foreach($meal_ids_add as $meal) 
            {   
                // If found out that the meal is in removed ids then exclude it
                // if (in_array($meal, $meal_ids_remove)) {
                //     continue;
                // }

                if ($this->meals->isVegetarian($meal)) {
                    $meals_veg[] = $meal;
                } else {
                    $meals[] = $meal;
                }
            }
        }

        $mergedMeals = array_merge($meals, $meals_veg);


        // to update meal ids to 0
        if (!empty($meals_ids_to_removed)) {
            $this->mealsRepository->deactivateMealsInArray($meals_ids_to_removed);
        }

        
        //to update meal ids to 1
        if (! empty($mergedMeals)) {
            $this->mealsRepository->activateMealsInArray($mergedMeals);
        } 


        return [$meals, $meals_veg];
    }
}
