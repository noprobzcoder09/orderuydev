<?php

namespace App\Services\Cutover\Cycle;

use App\Services\Cutover\Data\Cycle as ModelCycle;
use App\Services\Cutover\Data\MealStatus;
use App\Services\Cutover\Data\Meals as ModelMeals;


Class Meals
{   
    public function __construct(int $id)
    {
        $this->cycle = new ModelCycle;
        $this->mealStatus = new MealStatus;
        $this->meals = new ModelMeals;
        $this->id = $id;
    }

    public function handle()
    {   
        foreach($this->cycle->getById($this->id) as $cycle) 
        {
            foreach($this->mealStatus->get($cycle->id) as $row) 
            {   
                $meal_ids_add = json_decode($row->meal_ids_add);
                $meal_ids_remove = json_decode($row->meal_ids_remove);
                // 
                // Set status to inactive
                foreach($meal_ids_remove as $id) {
                    // $this->meals->updateStatus($id, 0);
                }

                // Set status to active
                foreach($meal_ids_add as $id) {
                    $this->meals->updateStatus($id, 1);
                }

            }
        }
    }
}
