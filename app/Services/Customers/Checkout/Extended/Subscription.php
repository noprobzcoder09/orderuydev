<?php

namespace App\Services\Customers\Checkout\Extended;

use App\Services\Manageplan\Subscription as SubscriptionParent;

Class Subscription extends SubscriptionParent
{      
    public function store()
    {        
        $this->validate($this->get());

        $this->setEloquentSubscription($this->repo->store($this->get()));    

        if (empty($this->repo->id)) {
            throw new Exception(sprintf(__('crud.failedToCreate'),'subscription'), 1);
        }
        
        $this->setId($this->repo->id);    
    }

    public function getEloquentSubscription()
    {
        return $this->eloquent;
    }

    public function setEloquentSubscription($eloquent)
    {
        $this->eloquent = $eloquent;
    }
}
