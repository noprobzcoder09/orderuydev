<?php

namespace App\Services\Manageplan;

use App\Services\Manageplan\Cycle;
use App\Services\Manageplan\DeliveryZoneTiming;
use App\Services\Manageplan\Subscription;
use App\Services\Manageplan\SubscriptionSelection;
use App\Services\Manageplan\Customer;

use App\Services\Manageplan\Contracts\Request as RequestAdapter;
use App\Services\Manageplan\Contracts\Auth as AuthAdapter;
use App\Services\Manageplan\Contracts\Order as OrderAdapter;
use App\Services\Manageplan\Contracts\Coupon as CouponAdapter;
use App\Services\Manageplan\Contracts\Discount as DiscountAdapter;
use App\Services\Manageplan\Contracts\Batch as BatchAdapter;
use App\Repository\MealsRepository;

Class SubscriptionFacade
{   
    public $status = 'active';
    public $cyclestatus = 'pending';

    public function __construct(
        RequestAdapter $request, 
        AuthAdapter $auth, 
        OrderAdapter $order,
        CouponAdapter $coupon,
        DiscountAdapter $discount,
        BatchAdapter $batch) { 
          
        $this->auth = $auth;
        $this->order = $order;
        $this->coupon = $coupon;
        $this->request = $request;
        $this->batch = $batch;
        $this->discount = $discount;
        $this->cycle = new Cycle;
        $this->DZTiming = new DeliveryZoneTiming;
        $this->subscription = new Subscription;
        $this->subscriptionSelection = new SubscriptionSelection;
        $this->mealRepository = new MealsRepository;
        $this->customer = new Customer;
    }

    
    public function create()
    {   

        $this->setSubscription();
        $this->subscription->store();

        $this->setDeliveryZoneTiming();
        $this->setCycle();

        if (!empty($this->coupon->get())) {
            $this->setDiscount();
            $this->discount->store();
        }

        $this->setSubscriptionSelection();

        $this->subscriptionSelection->store();

        $this->updateDeliveryZoneTimingId();

        $this->updateCustomerCycleIdForTheCurrentWeek();

        $this->updateDiscountNumberSubscriptions();
    }

    public function updateInvoice(string $invoiceId)
    {
        $this->subscriptionSelection->updateInvoice($this->subscriptionSelection->getId(), $invoiceId);
    }

    public function setSubscription()
    {
        $this->subscription->set(
            $this->auth->getId(),
            $this->order->getPlanId(),
            $this->order->getPrice(
                $this->order->getPlanId()
            ),
            $this->status
        );
    }

    public function setSubscriptionSelection()
    {
        $this->subscriptionSelection->set(
            $this->auth->getId(),
            $this->subscription->getId(),
            $this->cycle->getId(),
            $this->getDefaultMenu(),
            $this->DZTiming->getDeliveryZoneId(),
            $this->discount->getId(),
            $this->cyclestatus
        );
    }

    public function setDiscount()
    {

        $this->discount->set(
            json_encode($this->coupon->get()),
            $this->discount->getTotal(),
            $this->discount->getTotalRecur()
        );
    }

    public function setCycle()
    {
        $this->cycle->set(
            $this->DZTiming->getDeliveryTimingId()
        );
    }

    public function setDeliveryZoneTiming()
    {
        $this->DZTiming->set(
            $this->request->deliveryZoneTimingId()
        );
    }

    public function getSubscriptionSelectionId()
    {
        return $this->subscriptionSelection->getId();
    }

    public function getSubscriptionId()
    {
        return $this->subscription->getId();
    }

    public function updateDeliveryZoneTimingId()
    {
        $this->customer->updateDeliveryZoneTimingId(
            $this->auth->getId(), 
            $this->request->deliveryZoneTimingId()
        );
    }

    public function updateCustomerCycleIdForTheCurrentWeek()
    {
        $this->subscriptionSelection->updateCurrentSubscriptionWeekCycleId(
            $this->auth->getId(), 
            $this->cycle->getId(),
            $this->DZTiming->getDeliveryZoneId()
        );
    }

    public function updateDiscountNumberSubscriptions()
    {   
        $this->discount->updateDiscountNumberSubscriptions(
            $this->discount->getId(),
            count($this->order->get())
        );
    }

    public function getDefaultMenu()
    {
        $isVegetarian = $this->order->isVegetarian($this->order->getPlanId());
        $noMeals = $this->order->getNoMeals($this->order->getPlanId());

        $menu = $this->cycle->getDefaultMenu(
                $this->order->isVegetarian(
                    $this->order->getPlanId()
                )
            );

        // if ($isVegetarian) {
        //     $default = $this->mealRepository->getActiveMealsByActiveCycle($isVegetarian);  
        // } else {
        //     $default = $this->mealRepository->getActiveMealsByActiveCycle();
        // }

        $menu = is_array($menu) ? $menu : json_decode($menu);
        // $menu = empty($menu) ? [] : $menu;

        // $defaultMeals = array();
        // // Get meal ids
        // foreach($default as $row) {
        //     array_push($defaultMeals, $row->id);
        // }

        // $noMeals = $this->order->getNoMeals($this->order->getPlanId());

        // $currentNoMenus = count($menu);
        // $currentNoMenusLeft = 0;
        // if ($currentNoMenus < $noMeals) {
        //     $currentNoMenusLeft = $noMeals - $currentNoMenus;
        // }
        
        // for($i = 0; $i < $currentNoMenusLeft; $i++) {
        //     if (!isset($defaultMeals[$i])) {
        //         $i = 0;
        //         $currentNoMenusLeft = $noMeals - count($menu);
        //     }

        //     array_push($menu, $defaultMeals[$i]);
        // }
        
        return json_encode($menu);
    }
}

 