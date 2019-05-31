<?php

namespace App\Services\Reports\Criteria\Pickslips;

use App\Services\Reports\Joins;

Class WithPlans extends Joins
{      
	public function __construct()
	{
		
	}
	
	public function apply($model)
	{	
        $model->join('subscriptions',
            'subscriptions_cycles.subscription_id','=',
            'subscriptions.id'
        );

        $model->join('meal_plans',
            'meal_plans.id','=',
            'subscriptions.meal_plans_id'
        );

        return $model;
	}
}

