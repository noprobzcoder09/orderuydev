<?php

namespace App\Services\Customers\Subscriptions\Extended;

use App\Services\Manageplan\Request as RequestParent;
use Request as R;

Class Request extends RequestParent
{   
    public function getCardId()
    {
        return R::get('card_id');
    }

    public function getUserId()
    {
        return R::get('userid');
    }

    public function getCycleId()
    {
        return R::get('cycle_id');
    }    
}
