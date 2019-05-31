<?php

namespace App\Services\Customers\BillingIssue\Extended;

use Request as R;
use App\Services\Dashboard\Extended\Request as RequestParent;

Class Request extends RequestParent
{   
    public function getCardId()
    {
        return R::get('cardId');
    }

    public function getStatus()
    {
        return R::get('status');
    }

    public function getSubscriptionCycleId()
    {
        return R::get('subscriptionCycleId');
    }

    public function getSubscriptionCycleIds()
    {
        return R::get('subscriptionCycleIds');
    }

    public function getCycleId()
    {
        return R::get('cycle_id');
    }
}
