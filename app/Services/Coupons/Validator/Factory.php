<?php

namespace App\Services\Coupons\Validator;

use App\Services\Customers\Checkout\Extended\Coupon as CheckoutCoupon;
use App\Services\Customers\Checkout\Extended\Order as CheckoutOrder;
use App\Services\Manageplan\Coupon as SubscriptionCoupon;
use App\Services\Manageplan\Order as SubscriptionOrder;

use App\Services\Cutover\Billing\Extended\Coupon as RecurringCoupon;
use App\Services\Cutover\Billing\Extended\Order as RecurringOrder;

use App\Services\Customers\Account\Billing\Extended\Order as BillingOrder;
use App\Services\Customers\Account\Billing\Extended\Coupon as BillingCoupon;

use App\Rules\Coupon;
use App\Rules\RecurringCoupon as RulesRecurringCoupon;

Class Factory
{    
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function checkout()
    {
        return new Coupon(
            $this->userId,
            new CheckoutOrder,
            new CheckoutCoupon
         );
    }

    public function subscription()
    {
        return new Coupon(
            $this->userId, 
            new SubscriptionOrder, 
            new SubscriptionCoupon
        );
    }

    public function recurring()
    {
        return new RulesRecurringCoupon(
            $this->userId, 
            new RecurringOrder, 
            new RecurringCoupon
        );
    }

    public function unpaidBilling()
    {
        return new RulesRecurringCoupon(
            $this->userId, 
            new BillingOrder('unpaid-billing-order'), 
            new BillingCoupon('unpaid-billing-coupon')
        );
    }

    public function pendingBilling()
    {
        return new RulesRecurringCoupon(
            $this->userId, 
            new BillingOrder('pending-billing-order'), 
            new BillingCoupon('pending-billing-coupon')
        );
    }

    public function totalSharedDiscount()
    {
        return new RulesRecurringCoupon(
            $this->userId, 
            new BillingOrder('order-manageplan-display'), 
            new BillingCoupon('coupon-manageplan-display')
        );
    }

}
