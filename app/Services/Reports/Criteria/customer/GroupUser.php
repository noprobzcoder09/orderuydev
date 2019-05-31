<?php

namespace App\Services\Reports\Criteria\Customer;

use App\Services\Reports\Joins;

Class GroupUser extends Joins
{      
	public function apply($model)
	{	
       return $model->groupBy('subscriptions_cycles.user_id');
	}
}

