<?php

namespace App\Services\Sync;

use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Sync\GetContactByOldDeliveryZoneValue;
use App\Services\Sync\GetContacts;
use App\Services\Sync\Data;
use App\Services\Sync\LockInterface;

Abstract Class SyncAbstract
{       
    public abstract function handle(
        array $locations, 
        string $oldZoneName, 
        string $newZoneName, 
        string $oldDeliveryAddress, 
        string $newDeliveryAddress
    );
    public abstract function locked(LockInterface $lock);
    public abstract function data();
}
