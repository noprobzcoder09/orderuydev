<?php

namespace App\Services\Customers\Account;

class SpecificUpdate
{      
    public function updateCustomerActiveLocation()
    {
        $this->provider->updateCustomerActiveLocation();
    }

    public function updateCustomerActiveAddress()
    {
        $this->provider->updateCustomerActiveAddress();
    }

    public function updateCustomerDeliveryLocation()
    {
        $this->provider->updateCustomerDeliveryLocation();
    }

    public function updateCustomerDeliveryAddress()
    {
        $this->provider->updateCustomerDeliveryAddress();
    }

}

