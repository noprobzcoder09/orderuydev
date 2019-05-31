<?php

namespace App\Services\Customers\Account\Billing\Dto;

use App\Services\Customers\Account\Billing\Extended\Coupon;
use App\Services\Customers\Account\Billing\Extended\Order;
use App\Services\Customers\Account\Billing\Dto\CouponDto;

use App\Services\Manageplan\Worker;

Class CalculateSharedCouponDiscountDto
{   
    public $couponCollections = array();
    public $collections = array();
    public $totalPayment = 0;
    public $totalDiscount = 0;

    const SESSION_ORDER_REF = 'pending-billing-order';
    const SESSION_COUPON_REF = 'pending-billing-coupon';

    public function __construct(int $userId, array $subscriptions)
    {   
        $this->userId = $userId;
        $this->subscriptions = $subscriptions;
        $this->order = new Order(self::SESSION_ORDER_REF);
        $this->coupon = new Coupon(self::SESSION_COUPON_REF);
        $this->worker = new Worker($this->order, $this->coupon);

        $this->discountCollectionsSubscritionId();
        $this->iterateSharedDiscount();
    }   

    public function getTotalDiscount()
    {
        return $this->totalDiscount;
    }

    public function getTotalPayment()
    {
        return $this->totalPayment;
    }

    public function getRecurringDiscount()
    {
        return $this->couponCollections;
    }

    protected function discountCollectionsSubscritionId()
    {
        $collections = array();
        foreach($this->subscriptions as $subscription) {
            $collections[$subscription->getDiscountId()]['order'][] = $subscription;
            $collections[$subscription->getDiscountId()]['coupon'] = $subscription->getCoupons();
        }

        $this->collections = $collections;
    }

    protected function iterateSharedDiscount()
    {   
        $collections = array();

        foreach($this->collections as $collect) {
            foreach($collect['order'] as $order) {
                $this->order->setPrice($order->getProduct()->getPrice());
                $this->order->setSubscriptionId($order->getSubscriptionId());
                $this->order->store($order->getProduct()->getMealPlansId());
            }

            $coupons = new CouponDto($this->userId, $collect['coupon']);
            foreach($coupons->get() as $code) {
                $this->coupon->store($code); 
            }

            $this->worker = new Worker($this->order, $this->coupon);
            $this->totalPayment += $this->worker->getTotalAfterThisWeek();
            $this->totalDiscount += $this->worker->getTotalDiscount();

            array_push($this->couponCollections, $this->worker->getRecurringDiscountWithCalculatedPrice());

            $this->order->destroy();
            $this->coupon->destroy();
        }
    }

}
