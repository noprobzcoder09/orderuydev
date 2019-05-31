<?php

namespace App\Services\Cutover\Traits;

use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\InfusionsoftV2\Tag;
use App\Repository\MealsRepository;
use App\Repository\ProductPlanRepository;
use App\Repository\CustomerRepository;
use App\Services\Customer;
use App\Services\Log;
use Auth;

Trait InfusionEventNotifierProvider
{   
    private function eventInfusionsoftNotification($deliveryTimingsId)
    {
        $this->eventAddTagToContact($deliveryTimingsId);
    }

    private function eventAddTagToContact($deliveryTimingsId)
    {   
        $tag = new Tag;
        $customer = new CustomerRepository;
    
        $contacts = array();
        foreach($customer->getUsersForDeliverySubscriptionsByTiming($this->previousCycleId) as $row) {  
            if ($this->isSendActiveDeliveryTagLog($deliveryTimingsId, $row->ins_contact_id)) {
                $this->logActivityTagReport($deliveryTimingsId, $row->ins_contact_id);
                array_push($contacts, $row->ins_contact_id);
            }
        }
        
        if (!empty($contacts)) {
            $collection = collect($contacts);
            $chunks = $collection->chunk(100);
            foreach($chunks->toArray() as  $row) {
                $infusionsoftCustomer = new InfusionsoftCustomer(0);
                $infusionsoftCustomer->savedTagToContact(
                    $tag->getActiveMenuDeliveryId(), array_keys($row)
                );
            }
        }
        
        echo "ACTIVE DELIVERY TAG:<br>";
        print_r($contacts);
    }

    private function isSendActiveDeliveryTagLog($deliveryTimingsId, $contactId)
    {   
        $log = new Log('report_log','logs/active-delivery-tag.log');
        if (!file_exists(storage_path()."/logs/active-delivery-tag.log")) {
            $log->info('Report Log Created.');
        }

        $file = file(storage_path()."/logs/active-delivery-tag.log");
        
        foreach(array_reverse($file) as $record) {

            $record = explode('|', $record);
            if (count($record) < 2) continue;

            $deliveryTimingsIdLine = explode(' ', $record[0])[3];
            $contactIdLine =  explode(' ', $record[2])[0];
            $cycleIdLine =  explode(' ', $record[1])[0];
            
            if ($deliveryTimingsId == $deliveryTimingsIdLine) {
                if ($this->currentCycleId == $cycleIdLine) {
                    if ($contactId == $contactIdLine) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function logActivityTagReport($deliveryTimingsId, $contactId)
    {
        $log = new Log('report_log','logs/active-delivery-tag.log');
        $log->info($deliveryTimingsId.'|'.$this->currentCycleId.'|'.$contactId);
    }

}
