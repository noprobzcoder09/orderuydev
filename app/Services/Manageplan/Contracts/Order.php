<?php

namespace App\Services\Manageplan\Contracts;

Interface Order
{   
    public function get();
    public function store(int $planId, array $meals = []);
    public function getPlanId();
    public function getPrice(int $planId);
    public function isVegetarian(int $planId);
    public function getNoMeals(int $planId);
    public function getNoDays(int $planId);
    public function getMeals(int $planId);
    public function total();
    public function destroy();
}
