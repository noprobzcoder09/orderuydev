<?php

namespace App\Services\Sync\Sync\DeliveryZone;

use App\Services\InfusionsoftV2\CustomField;
use App\Services\Sync\Store;

Class DeliveryZoneStore
{       
    private $oldValue;
    private $newValue;

    public function __construct(string $oldZoneName, string $newZoneName, string $oldDeliveryAddress, string $newDeliveryAddress)
    {
        $this->oldZoneName = $oldZoneName;
        $this->newZoneName = $newZoneName;
        $this->oldDeliveryAddress = $oldDeliveryAddress;
        $this->newDeliveryAddress = $newDeliveryAddress;
    }

    public function handle()
    {   
        $store = new Store;
        $this->group1($store);
        $store->handle();

        $store = new Store;
        $this->group2($store);
        $store->handle();
    }

    private function group1($store)
    {   
        $customField = new CustomField; 
        $store->store($customField->getActiveLocation(), $this->oldZoneName, $this->newZoneName);
        $store->store($customField->getActiveDeliveryAddress(), $this->oldDeliveryAddress, $this->newDeliveryAddress);    
    }

    private function group2($store)
    {
        $customField = new CustomField; 
        $store->store($customField->getNextDeliveryLocation(), $this->oldZoneName, $this->newZoneName);
        $store->store($customField->getDeliveryAddress(), $this->oldDeliveryAddress, $this->newDeliveryAddress);    
    }
}
