<?php

namespace App\Services\Reports\Criteria\Pickslips;

use App\Services\Reports\Joins;
use App\Models\Meals;

Class Fields extends Joins
{      

	private $id = [];
	private $name = [];
	private $records = [];
	private $deliveries = [];

	private static $fields = [
		'user_details.user_id','menu_selections',
		'plan_name','delivery_date',
		'cutover_date',
		'first_name','last_name',
		'address1','address2',
		'state','postcode','subscription_id'
	];


	public function apply($model)
	{	
		return $model->select(static::$fields);
	}

}

