<?php

namespace App\Services\Customers\PreviousWeekSubscriptions\Extended;

use App\Services\Customers\Subscriptions\Extended\Request as RequestParent;
use Request as R;

Class Request extends RequestParent
{   
    public function getSubscriptionId()
    {
        return R::get('subscriptions_id');
    }
    
}
