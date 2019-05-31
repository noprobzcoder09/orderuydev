<?php

namespace App\Services\Customers\Subscriptions\Extended;

use App\Services\Manageplan\Coupon as CouponParent;

Class Coupon extends CouponParent
{   
    public function getCodeById(int $id)
    {
        return $this->repo->getCodeById($id);
    }

}
