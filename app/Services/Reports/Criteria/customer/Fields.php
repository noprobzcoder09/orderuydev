<?php

namespace App\Services\Reports\Criteria\Customer;

use App\Services\Reports\Joins;
use App\Models\Meals;
use DB;

Class Fields extends Joins
{      

	private $id = [];
	private $name = [];
	private $records = [];
	private $deliveries = [];

	private static $fields = [
		'first_name','last_name',
		'email','mobile_phone',
		'delivery_notes',
		'user_details.user_id',
		'subscriptions_cycles.cycle_id'
	];


	public function apply($model)
	{	
		return $model->select(static::$fields);
	}

}

