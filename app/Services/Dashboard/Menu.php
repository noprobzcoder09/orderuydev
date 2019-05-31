<?php

namespace App\Services\Dashboard;

use Request;
use App\Services\Validator;

class Menu
{      
    private $id;

    protected $subscriptionId;

    public $mealLunch = array();
    public $mealDinner = array();

    public $savedSelections = array();

    public function __construct(int $userId, int $subscriptionId, int $subscriptionCycleId)
    {   
        $this->setSubscriptionId($subscriptionId);
        $this->setSubscriptionCycleId($subscriptionCycleId);
        $this->setUserId($userId);

        $this->usersRepository = new \App\Repository\UsersRepository;
        $this->mealRepository = new \App\Repository\MealsRepository;
        $this->cycleRepository = new \App\Repository\CycleRepository();
        $this->subscriptionRepo = new \App\Repository\SubscriptionRepository;
        $this->subscriptionRepoSel = new \App\Repository\SubscriptionSelectionsRepository;        
        $this->planRepository = new \App\Repository\ProductPlanRepository;

        $this->init();
    }

    private function init()
    {
        $this->setSubscription($this->subscriptionRepo->get($this->getSubscriptionId()));
        $this->setSubscriptionSelection(
            $this->subscriptionRepoSel->getById($this->getSubscriptionCycleId())
        );

        $this->setSubscriptionMenu($this->getMenu());
    }

    protected function setSubscription($subscription) {
        $this->subscription = $subscription;
    }

    protected function setSubscriptionSelection($selections) {
        $this->selections = $selections;
    }

    protected function getSubscription() {
        return $this->subscription;
    }

    protected function getSelection() {
        return $this->selections;
    }

    protected function getSelectionId() {
        return $this->selections->id;
    }

    protected function getCycleId() {
        return $this->selections->cycle_id ?? 0;
    }

    protected function getMenu() {
        return $this->getSelection()->menu_selections ?? '';
    }

    protected function getPlanId() {
        return $this->getSubscription()->meal_plans_id;
    }

    protected function setSubscriptionId($subscriptionId) {
        $this->subscriptionId = $subscriptionId;
    }

    protected function getSubscriptionId() {
        return $this->subscriptionId;
    }

    protected function setSubscriptionCycleId($subscriptionCycleId) {
        $this->subscriptionCycleId = $subscriptionCycleId;
    }

    protected function getSubscriptionCycleId() {
        return $this->subscriptionCycleId;
    }

    protected function setUserId($userId) {
        $this->userId = $userId;
    }

    protected function getUserId() {
        return $this->userId;
    }

    protected function setSubscriptionMenu($menu) {
        $this->menu = $menu;
    }

    protected function getSubscriptionMenu() {
        return $this->menu;
    }

    public function getSavedSelections() {
        return $this->savedSelections;  
    }

    public function getCycle()
    {
        $activeBatch = (new \Configurations)->getActiveBatch();
        $timingId = $this->usersRepository->getDeliveryTimingId($this->usersRepository->getDeliveryZoneTimingId($this->userId));
        return $this->cycleRepository->getByTimingAndBatch($timingId, $activeBatch);
    }

    public function getDeliveryDate()
    {
        $d = $this->cycleRepository->getDeliveryDate($this->getCycleId());
        return date('l dS F Y', strtotime($d));
    }

    private function isCutoverDue(int $cycleId)
    {   
        $cycle = $this->cycleRepository->get($cycleId);

        $now = new \DateTime(date('Y-m-d'));
        $cutover = new \DateTime($cycle->cutover_date);
        $delivery = new \DateTime($cycle->delivery_date);
        
        if (($cutover <= $now)) {
            return true;
        }
        return false;
    }

    public function getUserActiveCycle()
    {
        return $this->usersRepository->getActiveCycleByDeliveryZoneTimings($this->getUserId());
    }

    public function saveSelections()
    {           
        $lunch = json_decode($this->mealLunch);
        $dinner = json_decode($this->mealDinner);
        $meals = array_merge($lunch, $dinner);

        $this->subscriptionRepoSel->updateSelections(
            $this->getUserId(),
            $this->getSelectionId(), 
            json_encode($meals)
        );

        $this->planRepository->setId($this->getPlanId());

        $this->savedSelections = array(
            'user_id' => $this->getUserId(),
            'subscription' => $this->getSubscription(),
            'plan' => $this->planRepository->getPlanName(),
            'meals' => $this->mealRepository->getMealsByIds($meals)
        );

    }
    
    public function selections(): array
    {    
        $response = [];
        $mealsSelected = [];
        $lunch = [];
        $dinner = [];
        $defaultMeals = [];

        $this->planRepository->setId($this->getPlanId());

        $noDays = $this->planRepository->getNoDays();
        $noMeals = $this->planRepository->getNoMeals();
        $plan = $this->planRepository->getPlanName();
        $photo = $this->planRepository->getPlanImage();
        $vegetarian = $this->planRepository->isVegetarian();

        $DZtimingsId = (new \App\Repository\UsersRepository)
                ->getDeliveryZoneTimingId($this->getUserId());

        $meals = json_decode($this->getSubscriptionMenu());
        
        if ($vegetarian) {
            $default = $this->mealRepository->getActiveVegetarian();
        } else {
            $default = $this->mealRepository->getActive();
        }

        // Get meal ids
        foreach($default as $row) {
            array_push($defaultMeals, $row->id);
        }

        // Determine the number of meals by no days to be stored
        // If lack of meals for dinner/lunch
        // then set a default meal from default meals
        // To ensure that there is no empty default selections
        $meals = is_null($meals) ? [] : $meals;
        $currentNoMenus = count($meals);
        $currentNoMenusLeft = 0;
        if ($currentNoMenus < $noMeals) {
            $currentNoMenusLeft = $noMeals - $currentNoMenus;
        }
        

        for($i = 0; $i < $currentNoMenusLeft; $i++) {
            if (!isset($defaultMeals[$i])) {
                $currentNoMenusLeft = $i;
                $i = 0;
            }
            array_push($meals, $defaultMeals[$i]);
        }

        $mealsLunch = $meals;
        $mealsDinner = $meals;
        $isDinnerOnly = ($noMeals/$noDays);
        $dinner = $meals;
        
        $lunch = $isDinnerOnly == 2 ? array_splice($mealsLunch,0, $noDays) : [];
        $dinner = $isDinnerOnly == 2 ? array_splice($mealsDinner, $noDays, $noMeals) : $dinner;
 
        $response = [
            'meals' => $default,
            'lunch' => $lunch,
            'dinner' => $dinner,
            'noMeals' => $noMeals,
            'noDays' => $noDays,
            'plan' => $plan,
            'deliveryDate'  => $this->getDeliveryDate(),
            'image' => url(str_replace('public','storage',$photo))
        ];
        
        return $response;
    }

    public function getPlans()
    {   
        $plans = [];
        foreach($this->subscriptionRepo->getByUserId($this->userId) as $row) {
            $plan = $this->planRepository->get($row->meal_plans_id);
            $plans[] = [
                'id' => $row->id,
                'planId' => $plan->id,
                'name' => $plan->plan_name,
                'price' => number_format($row->price,2),
                'quantity' => $row->quantity,
                'pausedDate' => !empty($row->paused_till) 
                                ? date('Y-m-d', strtotime($row->paused_till)) 
                                : '',
                'status' => $row->status,
                'vegetarian' => $plan->vegetarian ? ' Vegetarian' : ''
            ];
        }
           
        return $plans;
    }
    
    public function myPlansIdOnly()
    {
        $plans = [];
        foreach($this->subscriptionRepo->getMyPlans($this->userId)->get() as $row) {
            $plans[] = $row->meal_plans_id;
        }
        return $plans;
    }

    public function myPlans()
    {
        return $this->subscriptionRepo->getMyPlans($this->userId)->get();
    }

    public function isSubscribed()
    {
        $this->subscriptionSel = $this->subscriptionRepoSel
        ->getPreviousSelections($this->getUserId());
        return isset($this->subscriptionSel->subscription_id) ? true : false;
    }
}

