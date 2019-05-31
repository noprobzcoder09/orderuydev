<?php

namespace App\Services\Sync;

use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\InfusionsoftV2\OAuth2\OAuth2InfusionsoftService;
use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\Sync\SyncAbstract;
use App\Services\Sync\Lock;
use App\Services\Sync\Data;

Class Sync
{       
    private $api;
    public function __construct(SyncAbstract $sync)
    {
        $this->sync = $sync;  
    }

    public function run(OAuth2InfusionsoftService $api)
    {   
        $this->api = $api; 
        foreach($this->sync->data() as $row) {
            $this->sync(
                $row->syncId(),
                $row->syncContacts(),
                $row->syncFields()
            );
        }
    }

    public function handle(
        array $locations, 
        string $oldZoneName, 
        string $newZoneName, 
        string $oldDeliveryAddress, 
        string $newDeliveryAddress
    ) {   
        $this->sync->locked(new Lock);
        $this->sync->handle(
            $locations, 
            $oldZoneName, 
            $newZoneName, 
            $oldDeliveryAddress, 
            $newDeliveryAddress
        );
        $infusionsoftCustomer = new InfusionsoftCustomer;
        $infusionsoftCustomer->sync($this->sync);
    }

    private function sync(array $syncDataId, array $contacts, array $fields)
    {   
        $this->data = new Data; 

        $collection = collect($contacts);
        $contact = $collection->chunk(100);
        
        foreach($syncDataId as $id) {
            $this->data->progress($id);
        }
        foreach($contact->toArray() as $row) {
            foreach($row as $id) {
                $this->api->updateCustomFields(
                    $id,
                    $fields
                );
                foreach($syncDataId as $syncId) {
                    $this->data->updatedContact($syncId, $id);
                }
            }
        }
        foreach($syncDataId as $id) {
            $this->data->completed($id);
        }
    }

}
