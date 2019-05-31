<?php

namespace App\Services\Cutover\Data;

use App\Models\MealsStatusChange as Model;

Class MealStatus
{   
    public function __construct()
    {
        $this->meals = new Model;
    }

    public function get(int $cyleId)
    {
        return $this->meals->where(['cycle_id' => $cyleId])->get();
    }
    
}
