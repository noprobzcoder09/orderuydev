<?php

namespace App\Services\Customers\Subscriptions\Extended;

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
    public function __construct(
        RequestAdapter $request, 
        AuthAdapter $auth, 
        OrderAdapter $order,
        CouponAdapter $coupon,
        DiscountAdapter $discount,
        BatchAdapter $batch) { 
          
        parent::__construct($request, $auth, $order, $coupon, $discount, $batch);        
    }
}

