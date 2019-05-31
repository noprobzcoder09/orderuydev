<?php

namespace App\Services\Customers\Checkout\Traits;

use App\Repository\CustomerRepository;
use Auth;

Trait CustomerInfo
{   
 
    public function getCustomerAccountInfo()
    {   
        
        $customerRepository = new CustomerRepository;
        $customerData = $customerRepository->getAccount(Auth::id());

        $info = array(
            'first_name' => $customerData['details']->first_name ?? '',
            'last_name' => $customerData['details']->last_name ?? '',
            'mobile_phone' => $customerData['details']->mobile_phone ?? '',
            'email' => $customerData['account']->email ?? '',
            'address1' => $customerData['address']->address1 ?? '',
            'address2' => $customerData['address']->address2 ?? '',
            'suburb' => $customerData['address']->suburb ?? '',
            'state' => $customerData['address']->state ?? '',
            'postcode' => $customerData['address']->postcode ?? '',
            'delivery_notes' => $customerData['details']->delivery_notes ?? '',
            'delivery_zone_timings_id' => $customerData['details']->delivery_zone_timings_id ?? 0,
            'delivery_zone_id' => $customerData['details']->delivery_zone_id ?? 0,
            'delivery_timings_id' => $customerData['details']->delivery_timings_id ?? 0
        );

        return $info;
    }

}


