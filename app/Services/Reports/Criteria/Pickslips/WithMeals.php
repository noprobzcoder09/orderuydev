<?php

namespace App\Services\Reports\Criteria\Pickslips;

use App\Services\Reports\Joins;
use App\Models\Meals;

Class WithMeals extends Joins
{      

	private $id = [];
	private $name = [];
	private $records = [];
	private $deliveries = [];

	public function __construct()
	{
		$this->meals = new Meals;
		
	}

	public function apply($model)
	{	
		$this->setDeliveries($model);

		$this->retrieveDeliveries();

		return $this->getRecords();
	}

	private function retrieveDeliveries()
	{
	
		foreach($this->deliveries as $row) {
			$id = [];
			$meal = json_decode($row->menu_selections);
			foreach($meal as $m) {
				$id[] = $m;
			}

			$this->setMealId($id);
			$this->records[$row->user_id][$row->subscription_id] = $this->retrieveMeals(
				$this->getUniqueId($id)
			);
		}
	}

	private function retrieveMeals(array $id)
	{		
		$name = [];
		foreach($this->meals->whereIn('id', $id)->get() as $row) {
			$name[$row->id] = (object)[
				'name' => $row->meal_name. ($row->vegetarian ? '(V)' : ''),
				'quantity' => $this->quantity($row->id)
			];
		}

		return $name;
	}

	private function setMealId($ids)
	{
		$this->id = $ids;
	}


	private function quantity(int $id)
	{
		return array_reduce($this->id, function($carry, $item) use ($id) {
			if ( $id == $item) {
				$carry += 1;
			}
			return $carry;
		},0);
	}

	private function getUniqueId($id)
	{
		return array_unique($id);
	}

	private function setDeliveries($object)
	{
	
		$this->deliveries = $object->get();
	}

	private function getRecords()
	{
	
		return $this->records;
	}

	private function addSpace($string)
	{
		$space = '';
		for($i = 0; $i < 200 - strlen($string); $i++) {
			$space .= '&nbsp;';
		}
		return $space;
	}
}

