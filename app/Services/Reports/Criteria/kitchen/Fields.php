<?php

namespace App\Services\Reports\Criteria\Kitchen;

use App\Services\Reports\Joins;
use App\Models\Meals;

Class Fields extends Joins
{      

	private $id = [];
	private $name = [];
	private $records = [];
	private $deliveries = [];

	private static $fields = [
		'menu_selections'
	];


	public function apply($model)
	{	
		return $model->select(static::$fields);
	}

}

