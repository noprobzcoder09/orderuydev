<?php

namespace App\Services\Sync\Sync\DeliveryZone;

use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Sync\InfusionsoftContact;
use App\Services\InfusionsoftV2\OAuth2\OAuth2InfusionsoftService;
use App\Services\Sync\Sync\DeliveryZone\DeliveryZoneStore;
use App\Services\InfusionsoftV2\CustomField;
use App\Services\Sync\Sync;
use App\Services\Sync\SyncAbstract;
use App\Services\Sync\Lock;
use App\Services\Sync\Data;
use App\Services\Sync\GetContacts;
use App\Services\Sync\LockInterface;

Class DeliveryZoneSync extends SyncAbstract
{       
    private $locations = array();
    private $api;

    public function __construct(OAuth2InfusionsoftService $api = null) {
        $this->api = $api;
    }

    public function handle(
        array $locations, 
        string $oldZoneName, 
        string $newZoneName, 
        string $oldDeliveryAddress, 
        string $newDeliveryAddress
    ) {   
        $this->locations = $locations;
        $this->oldZoneName = $oldZoneName;
        $this->newZoneName = $newZoneName;
        $this->oldDeliveryAddress = $oldDeliveryAddress;
        $this->newDeliveryAddress = $newDeliveryAddress;

        $this->syncDeliveryZone($this->locations);

        $deliveryZoneStore = new DeliveryZoneStore(
            $this->oldZoneName, 
            $this->newZoneName, 
            $this->oldDeliveryAddress, 
            $this->newDeliveryAddress
        );  

        $deliveryZoneStore->handle();
    }

    public function locked(LockInterface $lock)
    {   
        $customField = new CustomField; 
        $lock->check($customField->getActiveLocation());
        $lock->check($customField->getNextDeliveryLocation());
        $lock->check($customField->getActiveDeliveryAddress());
        $lock->check($customField->getDeliveryAddress());
    }

    public function data()
    {   
        $this->data = new Data; 

        $group = array();
        $customField = new CustomField; 
        $where = array(
            $customField->getActiveLocation(),
            $customField->getNextDeliveryLocation(),
            $customField->getActiveDeliveryAddress(),
            $customField->getDeliveryAddress()
        );
        foreach($this->data->getPendingByField($where) as $row) {
            $group[$row->group][] = (object)array(
                'id' => $row->id,
                'field' => $row->field,
                'old_value' => $row->old_value,
                'new_value' => $row->new_value,
            );
        }
        
        $recordsToUpdate = array();
        foreach($group as $row) {

            if (count($row) < 2) continue; // do not sync fields that doesn't have a pair

            $syncId = array();
            $query = array(
                $row[0]->field => $row[0]->old_value // this is should be the location field
            );
            $fields = array(
                $row[0]->field => $row[0]->new_value,
                $row[1]->field => $row[1]->new_value
            );
            array_push($syncId, $row[0]->id);
            array_push($syncId, $row[1]->id);
            $contacts = new GetContacts($this->api, $query);
            array_push(
                $recordsToUpdate, new InfusionsoftContact(
                    $syncId,
                    $contacts->get(),
                    $fields
                )
            );
        }

        return $recordsToUpdate;
    }

    public function syncDeliveryZone(array $locations)
    {
        $customField = new CustomField;
        $this->api->updateCustomFieldValues(
            $customField->getNextDeliveryLocationId(),
            array('Values' => implode("\n", $locations))
        );

        $this->api->updateCustomFieldValues(
            $customField->getActiveLocationId(),
            array('Values' => implode("\n", $locations))
        );
    }


}
