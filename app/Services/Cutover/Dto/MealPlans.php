<?php

namespace App\Services\Cutover\Dto;

Class MealPlans
{   
    private $mealPlansId;
    private $noDays;
    private $noMeals;
    private $isVege;

    public function __construct(int $mealPlansId, int $noDays, int $noMeals, bool $isVege) {
        $this->mealPlansId = $mealPlansId;
        $this->noDays = $noDays;
        $this->noMeals = $noMeals;
        $this->isVege = $isVege;
    }

    public function getMealPlansId()
    {
        return $this->mealPlansId;
    } 

    public function getNodays()
    {
        return $this->noDays;
    }    

    public function getNoMeals()
    {
        return $this->noMeals;
    }  

    public function isVege()
    {
        return $this->isVege == 1;
    }    

}
