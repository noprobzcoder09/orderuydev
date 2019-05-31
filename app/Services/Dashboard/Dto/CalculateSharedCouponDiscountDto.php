<?php

namespace App\Services\Dashboard\Dto;

use App\Services\Dashboard\Extended\Coupon;
use App\Services\Dashboard\Extended\Order;
use App\Services\Dashboard\Dto\CouponDto;

use App\Services\Manageplan\Worker;

Class CalculateSharedCouponDiscountDto
{   
    private $coupon = array();
    private $collections = array();
    private $totalDiscount = array();
    private $numberOfOrders = array();

    public function __construct(int $userId, array $subscriptions)
    {   
        $this->userId = $userId;
        $this->subscriptions = $subscriptions;
        $this->order = new Order;
        $this->coupon = new Coupon;

        $this->discountCollectionsSubscritionId();
        $this->iterateSharedDiscount();
    }   


    public function getTotalDiscount(int $discountId)
    {
        return $this->totalDiscount[$discountId] ?? 0;
    }

    public function getNumberOfOrders(int $discountId)
    {
        return $this->numberOfOrders[$discountId] ?? 0;
    }

    private function discountCollectionsSubscritionId()
    {
        $collections = array();

        foreach($this->subscriptions as $subscription) {
            $collections[$subscription->getDiscountId()]['order'][] = $subscription;
            $collections[$subscription->getDiscountId()]['coupon'] = $subscription->getCoupons();
        }

        $this->collections = $collections;
    }

    private function iterateSharedDiscount()
    {   
        foreach($this->collections as $discountId => $collect) {
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
            $this->totalDiscount[$discountId] = $this->worker->getTotalRecurringDiscount();
            $this->numberOfOrders[$discountId] = count($this->order->get());
            $this->order->destroy();
            $this->coupon->destroy();
        }
    }

}
