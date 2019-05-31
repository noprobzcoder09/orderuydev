<?php

namespace App\Services\Customers\Checkout\Extended;

use App\Services\Manageplan\SubscriptionFacade as SubscriptionFacadeParent;

use App\Services\Manageplan\Contracts\Request as RequestAdapter;
use App\Services\Manageplan\Contracts\Auth as AuthAdapter;
use App\Services\Manageplan\Contracts\Order as OrderAdapter;
use App\Services\Manageplan\Contracts\Coupon as CouponAdapter;
use App\Services\Manageplan\Contracts\Discount as DiscountAdapter;
use App\Services\Manageplan\Contracts\Batch as BatchAdapter;

use App\Services\Customers\Checkout\Extended\Subscription;

Class SubscriptionFacade extends SubscriptionFacadeParent
{   
    public $cyclestatus = 'paid';
    private $invoiceID;
    private $planId;

    public function __construct(
        RequestAdapter $request, 
        AuthAdapter $auth, 
        OrderAdapter $order,
        CouponAdapter $coupon,
        DiscountAdapter $discount,
        BatchAdapter $batch) { 
          
        parent::__construct($request, $auth, $order, $coupon, $discount, $batch);

        $this->subscription = new Subscription;
    }

    public function create()
    {   
        $this->setDeliveryZoneTiming();
        $this->setCycle();

        if (!empty($this->coupon->get())) {
            $this->setDiscount();
            $this->discount->store();
        }

        foreach($this->order->get() as $planId => $row)
        {   
            $this->planId = $planId;
            $this->setSubscription();
            $this->subscription->store();

            $this->setSubscriptionSelection();
            $this->subscriptionSelection->store();

            $this->updateDeliveryZoneTimingId();

            $this->updateInvoice($this->getInvoiceId());

            $this->updateCustomerCycleIdForTheCurrentWeek();
        }

        $this->updateDiscountNumberSubscriptions();
    }

    public function setSubscription()
    {
        $this->subscription->set(
            $this->auth->getId(),
            $this->planId,
            $this->order->getPrice($this->planId),
            $this->status
        );
    }

    public function setSubscriptionSelection()
    {   
        $this->subscriptionSelection->set(
            $this->auth->getId(),
            $this->subscription->getId(),
            $this->cycle->getId(),
            json_encode($this->order->getMeals($this->planId)),
            $this->DZTiming->getDeliveryZoneId(),
            $this->discount->getId(),
            $this->cyclestatus
        );
    }

    public function setInvoiceId(int $invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

}

