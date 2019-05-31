<?php

namespace App\Services\Customers\PreviousWeekSubscriptions\Extended;

use App\Services\Manageplan\Contracts\Request as RequestAdapter;
use App\Services\Manageplan\Contracts\Auth as AuthAdapter;
use App\Services\Manageplan\Contracts\Order as OrderAdapter;
use App\Services\Manageplan\Contracts\Coupon as CouponAdapter;
use App\Services\Manageplan\Contracts\Discount as DiscountAdapter;
use App\Services\Manageplan\Contracts\Batch as BatchAdapter;

use App\Services\Customers\PreviousWeekSubscriptions\Extended\Subscription;
use App\Services\Customers\PreviousWeekSubscriptions\Extended\SubscriptionSelection;
use App\Services\Customers\PreviousWeekSubscriptions\Extended\Cycle;
use App\Services\Customers\Subscriptions\Extended\SubscriptionFacade as SubscriptionFacadeParent;

Class SubscriptionFacade extends SubscriptionFacadeParent
{   
    public $cyclestatus = 'paid';

    public function __construct(
        RequestAdapter $request, 
        AuthAdapter $auth, 
        OrderAdapter $order,
        CouponAdapter $coupon,
        DiscountAdapter $discount,
        BatchAdapter $batch) { 
          
        parent::__construct($request, $auth, $order, $coupon, $discount, $batch);

        $this->cycle = new Cycle;
        $this->subscriptionSelection = new SubscriptionSelection;
    }

    public function create()
    {   
        $this->setSubscriptionSelection();

        if ($this->subscriptionSelection->isExistCycle(
                $this->cycle->getId(), $this->getSubscriptionId())
            ) {
            $this->subscriptionSelection->updateByCycleSubscription(
                $this->cycle->getId(),
                $this->getSubscriptionId()
            );
        } else {
            $this->subscriptionSelection->store();
        }
    }

    public function setSubscriptionSelection()
    {
        $this->subscriptionSelection->set(
            $this->auth->getId(),
            $this->subscription->getId(),
            $this->cycle->getId(),
            $this->getDefaultMenu(),
            $this->request->deliveryZoneId(),
            $this->discount->getId(),
            $this->cyclestatus
        );
    }

    public function setSubscriptionId(int $id)
    {
        $this->subscription->setId($id);
    }

    public function setCycleId(int $id)
    {
        $this->cycle->setId($id);
    }
}

