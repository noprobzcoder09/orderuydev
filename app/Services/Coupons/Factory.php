<?php

namespace App\Services\Coupons;

use App\Services\Coupons\AbstractCouponFactory;

Class Factory implements AbstractCouponFactory
{   
    public function session(\App\Services\Session\AdapterInterface $session)
    { 
    	return  new  \App\Services\Coupons\Session($session);
    }

    public function db()
    { 
    	return  new  \App\Services\Coupons\Db;
    }

}
