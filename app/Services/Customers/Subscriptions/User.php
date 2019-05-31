<?php

namespace App\Services\Customers\Subscriptions;

use App\Repository\SubscriptionRepository;
use App\Repository\MealsRepository;

Class User
{   
    
    private $userId;

    public function __construct(int $userId)  
    {
        $this->userId = $userId;
    }

    public function getDeliveryMenu()
    {
        $subscription = new SubscriptionRepository;
        $meals = new MealsRepository;

        $data = '';
        foreach($subscription->getMyActivePlanName($this->userId) as $row) {
            $data .= $row->plan_name. "\n";
            foreach($meals->getByArray(json_decode($row->menu_selections)) as $meal) {
                $data .= $meal->meal_name. "\n";
            }

            $data .= "\n";
        }


        return $data;
    }
    
}


