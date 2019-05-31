<?php

namespace App\Services\Customers\PreviousWeekSubscriptions\Extended;

use App\Services\Manageplan\Subscription as SubscriptionParent;

Class Subscription extends SubscriptionParent
{   
    
    private $id;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

}
