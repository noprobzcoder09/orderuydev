<?php

namespace App\Services\Reports\Criteria\kitchen;

use App\Services\Reports\Request;
use App\Services\Reports\Joins;
use App\Models\Cycles;
use App\Models\Configurations;

Class ByPreviousCycle extends Joins
{      
    const PAID_STATUS = 'paid';
    const PENDING_STATUS = 'pending';
    
	public function __construct(int $previousCycle)
	{
        $this->config = new Configurations;
        $this->previousCycle = $previousCycle;
	}
	
	public function apply($model)
    {   
        return $model
        ->join('subscriptions_cycles',
            'subscriptions_cycles.user_id','=',
            'user_details.user_id'
        )
        ->join('subscriptions',
            'subscriptions_cycles.subscription_id', '=',
            'subscriptions.id'
        )
        ->join('meal_plans', 
            'subscriptions.meal_plans_id', '=',
            'meal_plans.id'
        )
        ->join('cycles',
            'cycles.id','=',
            'subscriptions_cycles.cycle_id'
        )
        ->whereIn('cycle_subscription_status',[self::PAID_STATUS, self::PENDING_STATUS])
        ->whereRaw("cycles.id in ('{$this->previousCycle}')");
    }

}

