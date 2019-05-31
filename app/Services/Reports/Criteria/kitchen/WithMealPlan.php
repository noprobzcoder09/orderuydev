<?php

namespace App\Services\Reports\Criteria\Kitchen;

use App\Services\Reports\Joins;
use App\Models\Meals;
use App\Models\Subscriptions;
use App\Models\SubscriptionsSelections;
use App\Models\MealPlans;

Class WithMealPlan extends Joins
{      

	private $id = [];
	private $name = [];
	private $data = [];
	private $sku = [];
	private $meal_plans_ids = [];
	private $meal_plan_name = [];


	public function __construct()
	{
		$this->meals = new Meals;

		$this->subscription = new Subscriptions;

		$this->subscriptionSelection = new SubscriptionsSelections;

		$this->meal_plan = new MealPlans;
		
	}

	public function apply($model)
	{	
        $subscriptions_data = $model->select('menu_selections', 'subscription_id', 'cycle_id', 'meal_plans_id', 'cycle_subscription_status');
		$this->retrieveMealPlan();

		$this->retrieveSkuAndMealName();

		$this->retrieveSubscriptionWithMealPlans($subscriptions_data);

		return $this->combine();
	}

	private function combine()
	{	

		$data = [];

		foreach($this->arr_meal_plans_qty as $outer_key => $meal_plans_qty) {

			foreach($meal_plans_qty as $inner_key => $meal_qty) {
                $Qty_Paid = $meal_qty["paid"] ?? 0;
                $Qty_Pending = $meal_qty["pending"] ?? 0;
                $Qty_Unpaid = $meal_qty["unpaid"] ?? 0;
				$data[] = (object)[
					'id' => $inner_key,
					'plan_name' => $this->meal_plan_name[$outer_key] ?? 'Deleted Meal',
					'name' => $this->name[$inner_key] ?? 'No Name',
					'sku' => $this->sku[$inner_key] ?? 'No SKU',
                    'Qty_Paid' => $Qty_Paid+$Qty_Pending,
                    'Qty_Unpaid' => $Qty_Unpaid,
				];
			}
		
		}
        
        $data = json_decode(json_encode($data), true);
        
        usort($data, function($a, $b){return strcmp($a["sku"], $b["sku"]);});
        for($i=0; $i<count($data); $i++){
            $data[$i] = (object)$data[$i];
        }

		return $data;
	}

	private function retrieveSkuAndMealName()
	{	
		$name = [];
		$sku = [];

		foreach($this->meals->whereNull('deleted_at')->select('id', 'meal_name', 'meal_sku', 'vegetarian')->get() as $row) {
			$name[$row->id] = $row->meal_name. ($row->vegetarian ? '(V)' : '');
			$sku[$row->id] = $row->meal_sku;
		}

		$this->name = $name;
		$this->sku = $sku;
		
	}


	private function retrieveMealPlan()
	{
		$meal_plans = [];
		$meal_plans_found = $this->meal_plan->select('plan_name', 'id');

		foreach ($meal_plans_found->get() as $meal_plan) {
			$meal_plans[$meal_plan->id] = $meal_plan->plan_name;
		}

		$this->meal_plan_name = $meal_plans;

	}

	private function retrieveSubscriptionWithMealPlans($object)
	{

		$arr_meal_plans_qty = [];

		foreach($object->get() as $meal_plan) {

            if(!isset($arr_meal_plans_qty[$meal_plan->meal_plans_id])){
                $arr_meal_plans_qty[$meal_plan->meal_plans_id] = [];
            }

            $menu_sel = json_decode($meal_plan->menu_selections);

            foreach ($menu_sel as $single_meals) {

                if (!isset($arr_meal_plans_qty[$meal_plan->meal_plans_id][$single_meals])){
                    $arr_meal_plans_qty[$meal_plan->meal_plans_id][$single_meals] = [];
                }

                if (!isset($arr_meal_plans_qty[$meal_plan->meal_plans_id][$single_meals][$meal_plan->cycle_subscription_status])){
                    $arr_meal_plans_qty[$meal_plan->meal_plans_id][$single_meals][$meal_plan->cycle_subscription_status] = 1;
                } else {
                    $arr_meal_plans_qty[$meal_plan->meal_plans_id][$single_meals][$meal_plan->cycle_subscription_status]++;
                }

            }
		}

		$this->arr_meal_plans_qty = $arr_meal_plans_qty;
//        echo "<pre>"; print_r($this->arr_meal_plans_qty); echo "</pre>"; exit;
	}

}

