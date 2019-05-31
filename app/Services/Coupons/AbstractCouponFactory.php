<?php

namespace App\Services\Coupons;

use Request;

Interface AbstractCouponFactory
{     
    
    public function session(\App\Services\Session\AdapterInterface $session);

    public function db();

}
