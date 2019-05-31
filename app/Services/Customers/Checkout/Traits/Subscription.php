<?php

namespace App\Services\Customers\Checkout\Traits;

Trait Subscription
{   
 
    public function createSubcsription()
    {       
        $this->batch->set((new \Configurations)->getActiveBatch());
        $this->discount->setTotal($this->worker->getTotalDiscount());
        $this->discount->setTotalRecur($this->worker->getTotalRecurringDiscount());
        $this->subscriptionFacade->setInvoiceId($this->billing->getInvoiceId());
        $this->subscriptionFacade->create();
    }

}


