<?php

namespace App\Services\Customers\Account\Infusionsoft;

use App\Services\Sync\SyncAbstract;

class QueueProvider
{      
    private $userId;
    
    public function __construct(int $userId = 0)
    {
        $this->userId = $userId;
    }

    public function updateCustomerInfs($newStatus = '')
    {
        \App\Jobs\InfusionCustomerUpdate::dispatch($this->userId, $newStatus)
        ->delay(now()->addMinutes(1));
    }

    public function updateCustomerDeliveryDetailsInfs()
    {
        \App\Jobs\InfusionCustomerUpdateDeliveryDetails::dispatch($this->userId)
        ->delay(now()->addMinutes(1));
    }

    public function updateCustomerDeliveryLocationWithAddressOnlyInfs() 
    {
        \App\Jobs\InfusionCustomerUpdateDeliveryLocationWithAddressOnly::dispatch($this->userId)
        ->delay(now()->addMinutes(1));
    }

    public function updateCustomerDeliveryMenuOnlyInfs() 
    {
        \App\Jobs\InfusionCustomerUpdateDeliveryMenuOnly::dispatch($this->userId)
        ->delay(now()->addMinutes(1));
    }

    public function updateStatus(string $newStatus = '')
    {
        \App\Jobs\InfusionCustomerUpdateStatus::dispatch($this->userId, $newStatus)
        ->delay(now()->addMinutes(1));
    }

    public function updatePausedCancelledPlans()
    {
        \App\Jobs\InfusionCustomerUpdateCancelledPausedPlans::dispatch(
            $this->userId
        )
        ->delay(now()->addMinutes(1));
    }

    public function savedTagToContact($tag, array $contacts)
    {
        \App\Jobs\InfusionCustomerAddTag::dispatch(
            $tag, $contacts
        )
        ->delay(now()->addMinutes(1));
    }

    public function cancelledWeek()
    {
        \App\Jobs\InfusionCustomerCancelledWeek::dispatch(
            $this->userId
        )
        ->delay(now()->addMinutes(1));
    }

    public function pausedAPlan()
    {
        \App\Jobs\InfusionCustomerPausedAPlan::dispatch(
            $this->userId
        )
        ->delay(now()->addMinutes(1));
    }

    public function cancelledAPlan()
    {
        \App\Jobs\InfusionCustomerCancelledAPlan::dispatch(
            $this->userId
        )
        ->delay(now()->addMinutes(1));
    }

    public function activeMenuDelivery()
    {
        \App\Jobs\InfusionCustomerActiveMenuDelivery::dispatch(
            $this->userId
        )
        ->delay(now()->addMinutes(1));
    }

    public function updateContact()
    {   
        \App\Jobs\InfusionCustomerUpdateContact::dispatch(
            $this->userId
        )
        ->delay(now()->addMinutes(1));
    }

    public function sync(SyncAbstract $sync)
    {   
        \App\Jobs\InfusionsoftCustomerSync::dispatch(
            $sync
        )
        ->delay(now()->addMinutes(1));
    }

}

