<?php

namespace App\Services\Customers\Subscriptions\Traits;

use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\Customers\Subscriptions\User;

Trait InfusionsoftCustomerProvider 
{   
    
    public function infusionsoftCustomerProvider()
    {   
        $infusionsoft = new InfusionsoftCustomer($this->auth->getId());
        $infusionsoft->updateCustomerInfs();
        $infusionsoft->updateCustomerDeliveryDetailsInfs();
    }
    
}


