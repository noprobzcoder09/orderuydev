<?php

namespace App\Services\Customers\Checkout\Traits;

use App\Services\Customers\Account\InfusionsoftCustomer;
use Auth;

Trait InfusionsoftCustomerUpdate
{   
 
    public function infusionsoftCustomerUpdate()
    {   
        $infusionsoftCustomer = new InfusionsoftCustomer(Auth::id());
        $infusionsoftCustomer->updateCustomerInfs();
    }

}


