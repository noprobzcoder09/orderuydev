<?php

namespace App\Services\Reports\Criteria\Kitchen;

use App\Services\Reports\Joins;
use App\Models\Meals;
use App\Models\Subscriptions;
use App\Models\SubscriptionsSelections;
use App\Models\MealPlans;

Class WithPaidAndBillingIssue extends Joins
{      

	private $id = [];
	private $name = [];
	private $data = [];
	private $sku = [];
	private $mealIds = [];
	private $arr_menu_paid_qty = [];
	private $arr_menu_billing_issue_qty = [];
	

	public function __construct()
	{
		$this->meals = new Meals;

		$this->subscription = new Subscriptions;

		$this->subscriptionSelection = new SubscriptionsSelections;

		$this->meal_plan = new MealPlans;
		
	}

	public function apply($model)
	{	
		$this->retrieveMealId($model->select('menu_selections', 'subscription_id'));

		$this->retrieveSkuAndMealName($this->getUniqueId());

		$this->retrieveSubscriptionWithMealPlansPaidUnpaid($model->select('menu_selections', 'subscription_id', 'cycle_id'));
		
		return $this->combine();
	}

	private function combine()
	{	

		
		$data = [];

		
		$data = [];
		foreach(array_unique($this->mealIds) as $id) {
			$data[] = (object)[
				'id' => $id,
				'name' => $this->name[$id] ?? 'No Name',
				'sku' => $this->sku[$id],
				'paid' => !empty($this->arr_menu_paid_qty[$id] ) ? $this->arr_menu_paid_qty[$id] : 0 ,
				'billing_issue' => !empty($this->arr_menu_billing_issue_qty[$id] ) ? $this->arr_menu_billing_issue_qty[$id] : 0
			];
		}

		


		return $data;

		
	}

	private function retrieveMealId($object)
	{
		$id = [];

		foreach($object->get() as $row) {
			$meal = json_decode($row->menu_selections);
			foreach($meal as $m) {
				$id[] = $m;
			}
		}

		$this->id = $id;
	}

	private function retrieveSkuAndMealName(array $id)
	{	
		$name = [];
		$sku = [];
		$mealId = [];

		foreach($this->meals->whereIn('id', $id)->whereNull('deleted_at')->get() as $row) {
			$name[$row->id] = $row->meal_name. ($row->vegetarian ? '(V)' : '');
			$sku[$row->id] = $row->meal_sku;
			$mealId[$row->id] = $row->id;
		}

		$this->name = $name;
		$this->sku = $sku;
		$this->mealIds = $mealId;

		
	}


	private function retrieveMealPlan(array $ids)
	{
		$meal_plans = [];
		$meal_plans_found = $this->meal_plan->whereIn('id', $ids)->get();
		foreach ($meal_plans_found as $meal_plan) {
			$meal_plans[$meal_plan->id] = $meal_plan->plan_name;
		}

		$this->meal_plan_name = $meal_plans;
	}

	private function retrieveSubscriptionWithMealPlansPaidUnpaid($object)
	{
		$menu = [];
		$arr_menu_paid_qty = [];
		$arr_menu_billing_issue_qty = [];
		

		foreach($object->get() as $row) {

			$subscriptions = $this->subscription->leftJoin('subscriptions_cycles', 'subscriptions_cycles.subscription_id', '=', 'subscriptions.id')
											->where('subscriptions.id', $row->subscription_id)
											->where('cycle_id', $row->cycle_id)
											->where(function($query){
												$query->whereIn('subscriptions.status', ['pending', 'active', 'billing issue'])
													->where(function($query) {
														$query->whereIn('subscriptions_cycles.cycle_subscription_status', ['paid', 'unpaid']);
													});
											})
											
											->get();

			
		
			foreach ($subscriptions as $subscription) {

				$menu_sel = json_decode($subscription->menu_selections);

					if ($subscription->cycle_subscription_status == 'paid') {
						foreach ($menu_sel as $single_meals) {

							if(!isset($arr_menu_paid_qty[$single_meals])){
								$arr_menu_paid_qty[$single_meals] = array();
							}
		
							if (in_array($single_meals, array_keys($this->sku))){
								
								
								
								if (empty($arr_menu_paid_qty[$single_meals])){
								
									$arr_menu_paid_qty[$single_meals] = 1;
								} else {
									$arr_menu_paid_qty[$single_meals]++;
								}
		
							}
						}
					} else {
						foreach ($menu_sel as $single_meals) {

							if(!isset($arr_menu_billing_issue_qty[$single_meals])){
								$arr_menu_billing_issue_qty[$single_meals] = array();
							}
		
							if (in_array($single_meals, array_keys($this->sku))){
								
								
								
								if (empty($arr_menu_billing_issue_qty[$single_meals])){
								
									$arr_menu_billing_issue_qty[$single_meals] = 1;
								} else {
									$arr_menu_billing_issue_qty[$single_meals]++;
								}
		
							}
						}
					}
					
				
			}
		}

		$this->arr_menu_paid_qty = $arr_menu_paid_qty;
		$this->arr_menu_billing_issue_qty = $arr_menu_billing_issue_qty;
	}



	
	private function getUniqueId()
	{
		return array_unique($this->id);
	}

	private function getUniqueMealPlanId(array $ids)
	{
		return array_unique($ids);
	}

}

