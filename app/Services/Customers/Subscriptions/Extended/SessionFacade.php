<?php

namespace App\Services\Customers\Subscriptions\Extended;

use App\Services\Manageplan\SessionFacade as SessionFacadeParent;

Class SessionFacade extends SessionFacadeParent
{   
    public function __construct(
        \App\Services\Manageplan\Contracts\Request $request, 
        \App\Services\Manageplan\Contracts\Auth $auth, 
        \App\Services\Manageplan\Contracts\Order $order, 
        \App\Services\Manageplan\Contracts\Coupon $coupon) { 

        parent::__construct($request, $auth, $order, $coupon);
    }

    public function store()
    {   
        foreach($this->request->getPromoCode() as $id) {
            $this->coupon->store($this->coupon->getCodeById($id));
        }

        $this->order->store($this->request->getPlanId());
    }  
}

