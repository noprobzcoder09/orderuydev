<?php

namespace App\Services\Manageplan;

use App\Services\Manageplan\Coupon;
use App\Services\Manageplan\Order;
use App\Services\Manageplan\Cycle;
use App\Services\Manageplan\DeliveryZoneTiming;
use App\Services\Manageplan\Subscription;
use App\Services\Manageplan\SubscriptionSelection;

Class SessionFacade
{   
    public function __construct(
        \App\Services\Manageplan\Contracts\Request $request, 
        \App\Services\Manageplan\Contracts\Auth $auth, 
        \App\Services\Manageplan\Contracts\Order $order, 
        \App\Services\Manageplan\Contracts\Coupon $coupon) { 
          
        $this->auth = $auth;
        $this->order = $order;
        $this->coupon = $coupon;
        $this->request = $request;
    }

    public function store()
    {
        $this->coupon->store($this->request->getPromoCode());
        $this->order->store($this->request->getPlanId());
    }  

    public function destroy()
    {
        $this->coupon->destroy();
        $this->order->destroy();
    }

}

