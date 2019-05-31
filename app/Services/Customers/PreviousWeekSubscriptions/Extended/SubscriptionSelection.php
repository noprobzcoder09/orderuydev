<?php

namespace App\Services\Customers\PreviousWeekSubscriptions\Extended;

use App\Services\Manageplan\SubscriptionSelection as SubscriptionSelectionParent;

Class SubscriptionSelection extends SubscriptionSelectionParent
{   
    
    public function updateToPaid(int $subscriptionCycleId)
    {
        if($this->repo->updateToPaid($subscriptionCycleId) <= 0) {
           throw new \Exception(__('crud.failedToUpdate'),' cycle status', 1);
        }
    }

    public function updateByCycleSubscription(int $cycleId, int $subscriptionId)
    {        
        $this->validate($this->get());
        
        $status = $this->repo->updateByCycleSubscription($cycleId, $subscriptionId, $this->get());    

        if ($status == 0) {
            throw new \Exception(sprintf(__('crud.failedToUpdate'),'subscription selection'), 1);
        }

        $this->setId($this->repo->getIdByCycleSubscription(
            $cycleId, $subscriptionId
        ));
    }

}
