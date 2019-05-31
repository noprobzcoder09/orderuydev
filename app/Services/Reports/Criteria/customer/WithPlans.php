<?php

namespace App\Services\Reports\Criteria\Customer;

use App\Services\Reports\Joins;
use DB;

Class WithPlans extends Joins
{      
    const PAID_STATUS = 'paid';
    const PENDING_STATUS = 'pending';

    private $customerPlans = array();

	public function __construct($model)
	{
        $this->apply($model);
	}
	
	private function apply($model)
	{	
        $customerPlans = array();
        foreach($model->get() as $row) {
            $mealPlansId = DB::table('subscriptions')->select('meal_plans_id')
            ->join('subscriptions_cycles','subscriptions_cycles.subscription_id','=','subscriptions.id')
            //->where('cycle_subscription_status', self::PAID_STATUS)
            ->whereIn('cycle_subscription_status', [self::PAID_STATUS, self::PENDING_STATUS])
            ->where('subscriptions.user_id',$row->user_id)
            ->where('subscriptions_cycles.cycle_id',$row->cycle_id)->get();

            foreach($mealPlansId as $id) {
                $customerPlans[$row->user_id][] = DB::table('meal_plans')->where('id',$id->meal_plans_id)->first()->plan_name ?? '';
            }
        }
        
        return $this->customerPlans = $customerPlans;
	}

    public function getCustomerPlans()
    {
        return $this->customerPlans;
    }
}

