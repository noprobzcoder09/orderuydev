<?php

namespace App\Services\Dashboard;

use App\Services\Dashboard\Extended\Request;
use App\Services\Dashboard\Extended\User;
use App\Services\Customers\Account\InfusionsoftCustomer;

class Delivery
{      
    public function __construct(int $userId)
    {
        $this->id = $userId;
        $this->user = new User($userId);
        $this->request = new Request;
    }

    public function update()
    {
        try 
        {   
            $this->user->updateDeliveryNotes($this->request->getDeliveryNotes());
            $this->user->updateDeliveryZoneTiming($this->request->getDeliveryZoneTimingId());
            $this->user->updateCurrentSubscriptionWeek($this->request->getDeliveryZoneTimingId());

            $infusionsoft = new InfusionsoftCustomer($this->id);
            $infusionsoft->updateCustomerInfs();

            return ['success' => true, 'message' => sprintf(__('crud.updated'),'delivery')];
        }
        catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateDeliveryZoneTimingIdOnly()
    {
        try 
        {   
            $this->user->updateDeliveryZoneTiming($this->request->getDeliveryZoneTimingId());
            $this->user->updateCurrentSubscriptionWeek($this->request->getDeliveryZoneTimingId());
            
            $infusionsoft = new InfusionsoftCustomer($this->id);
            $infusionsoft->updateCustomerInfs();

            return ['success' => true, 'message' => sprintf(__('crud.updated'),'delivery zone timing.')];
        }
        catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    
}

