<?php

namespace App\Services\Reports;

use Request;

Trait Types
{     
	protected static $types = [
		'Combined Kitchen Reports', 'Combined Customer Report',
		'Customer Report', 'Pick Slips', 
		'Active & Paid', 'Kitchen Meal Split Report'
	];
	

	protected function getTypes() {
		return static::$types;
	}

	protected function combineKitchen()
	{
		return static::$types[0];
	}

	protected function combineCustomer()
	{
		return static::$types[1];
	}

	protected function customer()
	{
		return static::$types[2];
	}

	protected function pickSlips()
	{
		return static::$types[3];
	}

	protected function activePaid()
	{
		return static::$types[4];
	}

	protected function kitchenMealSplit()
	{
		return static::$types[5];
	}

	protected function getType() {
		return Request::get('reports');
	}


}
