<?php

namespace App\Services\Customers\Checkout\Traits;

use Auth;

Trait Tags
{   
    public function sendTagNewCustomer()
    {
        \Tags::newCustomer($this->contact->getId());
    }
}


