<?php

namespace App\Services\Manageplan;

use Request as R;

Class Request implements \App\Services\Manageplan\Contracts\Request
{   
   	public function getPromoCode()
   	{
   		return R::get('coupons');
   	}

   	public function getPlanId()
   	{
   		return R::get('meal_plans_id');
   	}

    public function deliveryZoneTimingId()
    {
        return R::get('delivery_zone_timings_id');
    }

    public function deliveryZoneId()
    {
        return R::get('delivery_zone_id');
    }
    
}
