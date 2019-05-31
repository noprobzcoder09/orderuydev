<?php

namespace App\Services\Reports\Criteria\Kitchen;

use App\Services\Reports\Joins;
use App\Models\Meals;

Class WithMeals extends Joins
{      

	private $id = [];
	private $name = [];
	private $data = [];
	private $sku = [];

	public function __construct()
	{
		$this->meals = new Meals;
	}

	public function apply($model)
	{	
		$this->retrieveMealId($model->select('menu_selections', 'cycle_subscription_status'));

		$this->retrieveSkuAndMealName($this->getUniqueId());
//dd($this->combine());
		return $this->combine();
	}

	private function combine()
	{	
		$data = [];
		foreach($this->meal_id_qty as $id => $qty) {
            $Qty_Paid = $qty["paid"] ?? 0;
            $Qty_Pending = $qty["pending"] ?? 0;
            $Qty_Unpaid = $qty["unpaid"] ?? 0;

			$data[] = (object)[
				'id' => $id,
				'name' => $this->name[$id] ?? 'No Name',
				'sku' => $this->sku[$id] ?? 'No SKU',
				'Qty_Paid' => $Qty_Paid+$Qty_Pending,
				'Qty_Unpaid' => $Qty_Unpaid,
			];
		}

		return $data;
	}

	private function retrieveMealId($object)
	{
		$id = [];
        
        $meal_id_qty = [];

		foreach($object->get() as $row) {
			$meal = json_decode($row->menu_selections);

			foreach($meal as $m) {
				$id[] = $m;
                
                if(!isset($meal_id_qty[$m])){
                        $meal_id_qty[$m] = [];
                }

                if(!isset($meal_id_qty[$m][$row->cycle_subscription_status])){
                        $meal_id_qty[$m][$row->cycle_subscription_status] = 1;
                }
                else{
                    $meal_id_qty[$m][$row->cycle_subscription_status]++;
                }
			}
		}

        $this->meal_id_qty = $meal_id_qty;
		$this->id = $id;
	}

	private function retrieveSkuAndMealName(array $id)
	{		
		$name = [];
		$sku = [];
		foreach($this->meals->whereIn('id', $id)->get() as $row) {
			$name[$row->id] = $row->meal_name. ($row->vegetarian ? '(V)' : '');
			$sku[$row->id] = $row->meal_sku;
		}

		$this->name = $name;
		$this->sku = $sku;
	}


	private function getUniqueId()
	{
		return array_unique($this->id);
	}


}

