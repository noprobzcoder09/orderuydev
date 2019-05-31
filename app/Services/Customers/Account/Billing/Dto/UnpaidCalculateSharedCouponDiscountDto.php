<?php

namespace App\Services\Customers\Account\Billing\Dto;

use App\Services\Customers\Account\Billing\Extended\Coupon;
use App\Services\Customers\Account\Billing\Extended\Order;
use App\Services\Customers\Account\Billing\Dto\UnpaidCouponDto;

use App\Services\Manageplan\Worker;

use App\Services\Customers\Account\Billing\Dto\CalculateSharedCouponDiscountDto;

Class UnpaidCalculateSharedCouponDiscountDto extends CalculateSharedCouponDiscountDto
{   
    const SESSION_ORDER_REF = 'unpaid-billing-order';
    const SESSION_COUPON_REF = 'unpaid-billing-coupon';

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

    protected function iterateSharedDiscount()
    {   
        $collections = array();
        foreach($this->collections as $collect) {
            foreach($collect['order'] as $order) {
                $this->order->setPrice($order->getProduct()->getPrice());
                $this->order->setSubscriptionId($order->getSubscriptionId());
                $this->order->store($order->getProduct()->getMealPlansId());
            }

            $coupons = new UnpaidCouponDto($this->userId, $collect['coupon']);
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

