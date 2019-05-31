<?php

namespace App\Services\Customers\Account;

use App\Services\Customers\Account\Infusionsoft\Data;
use App\Services\Customers\Account\Infusionsoft\QueueProvider;
use App\Services\Customers\Account\Infusionsoft\InlineProvider;
use App\Services\Sync\SyncAbstract;

class InfusionsoftCustomer
{      
    private $userId;
    private $eventType;

    public function __construct(int $userId = 0, string $eventType = 'queue')
    {
        $eventType = strtolower($eventType);
        if ($eventType == 'queue') {
            $this->provider = new QueueProvider($userId);
        } else {
            $this->provider = new InlineProvider(new Data($userId));
        }
    }

    public function updateContact()
    {
        $this->provider->updateContact();
    }

    public function savedTagToContact($tag, array $contacts)
    {
        $this->provider->savedTagToContact($tag, $contacts);
    }

    public function activeMenuDelivery()
    {
        $this->provider->activeMenuDelivery();
    }

    public function updateStatus($newStatus = '')
    {
        $this->provider->updateStatus($newStatus);
    }

    public function updatePausedCancelledPlans()
    {
        $this->provider->updatePausedCancelledPlans();
    }

    public function updateCustomerInfs($newStatus = '')
    {
        $this->provider->updateCustomerInfs();
        $this->provider->updateStatus($newStatus);
        $this->provider->updatePausedCancelledPlans();
    }

    public function updateCustomerDeliveryDetailsInfs()
    {   
        $this->provider->updateCustomerDeliveryDetailsInfs();
    }

    public function updateCustomerDeliveryLocationWithAddressOnlyInfs()
    {
        $this->provider->updateCustomerDeliveryLocationWithAddressOnlyInfs();
    }

    public function updateCustomerDeliveryMenuOnlyInfs()
    {
        $this->provider->updateCustomerDeliveryMenuOnlyInfs();
    }

    public function updateCustomerActiveLocationWithAddress()
    {
        $this->provider->updateCustomerActiveLocationWithAddress();
    }

    public function updateCustomerDeliveryLocationWithAddress()
    {
        $this->provider->updateCustomerDeliveryLocationWithAddress();
    }

    public function sync(SyncAbstract $sync) {
        $this->provider->sync($sync);
    }

    public function cancelledWeek() {
        $this->provider->cancelledWeek();
    }

    public function pausedAPlan() {
        $this->provider->pausedAPlan();
    }

    public function cancelledAPlan() {
        $this->provider->cancelledAPlan();
    }
    
}

