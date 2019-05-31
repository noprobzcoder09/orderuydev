<?php

namespace App\Services\Reports\Criteria\Kitchen;

use App\Services\Reports\Joins;
use App\Models\SubscriptionsSelections;


Class WithPaid extends Joins
{      

	public function __construct()
	{
		$this->subscriptionCycles = new SubscriptionsSelections;
		
	}

	public function apply($model)
	{	

		return $this->withPaid($model);
	}


	private function withPaid($model) 
	{
		$paid = [];
		foreach($model as $data) {

			dd($data);

			$withPaid = $this->subscriptionCycles->where('menu_selections', 'like', "%{$data->id}%")->where('subscriptions_cycles.cycle_subscription_status', '=', 'paid')->count();
			$paid[$data->id] = $withPaid;
		}

		return $paid;
	}

}

