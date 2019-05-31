<?php

namespace App\Services\Reports\Criteria\Customer;

use App\Services\Reports\Joins;
use App\Services\Reports\Request;

Class ByLocation extends Joins
{   
	public function __construct($location = '')
	{
		$this->location = $location;
	}

	public function apply($model)
	{	
		if (empty($this->location)) 
            return $model;

        return $model->where('subscriptions_cycles.delivery_zone_id', $this->location);
	}
}

