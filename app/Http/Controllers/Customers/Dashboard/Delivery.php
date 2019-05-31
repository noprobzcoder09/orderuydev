<?php

namespace App\Http\Controllers\Customers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Delivery as DeliveryManager;
use Auth;
use App\Traits\Auditable;

class Delivery extends Controller
{   
    use Auditable;
    /*
    |--------------------------------------------------------------------------
    | Dashboard Customer Delivery Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling updates for delivery details
    |
    */

    public function update()
    {   
        $manager = new DeliveryManager(Auth::id());
        $this->audit('User Update Delivery Zone', 'The user updated his/her delivery zone.', '');
        return $manager->update();
    }

    public function updateDeliveryZoneTimingId()
    {
        $manager = new DeliveryManager(Auth::id());
        return $manager->updateDeliveryZoneTimingIdOnly();   
    }

}
