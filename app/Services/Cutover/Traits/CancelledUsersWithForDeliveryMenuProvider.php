<?php

namespace App\Services\Cutover\Traits;

use App\Services\Cutover\Billing\Consolidate;
use App\Services\Cutover\Billing\Discount;
use App\Services\Cutover\Formatter\Merge;
use App\Services\Cutover\Billing\Bill;

use App\Services\Cutover\Dto\SubscriptionsStatus as SubscriptionsStatusDto;
use App\Services\Cutover\Dto\SubscriptionsSelections as SubscriptionsSelectionsDto;
use App\Services\Cutover\Dto\SubscriptionsCycleStatus;

use App\Services\Customers\Account\InfusionsoftCustomer;

use App\Services\Log;

Trait CancelledUsersWithForDeliveryMenuProvider
{   
    private static $deliveryDetailsLogFilename = 'delivery-details';

    private function cancelledUsersWithForDeliveryMenu()
    {   
        $this->user->getCancelledUsers()->chunk(100, function($users)
        {   
            $data = array();
            $contacts = array();
            foreach($users as $user)
            {
                foreach($this->subscriptions->getByUser($user->user_id) as $sub)
                {
                    $selections = $this->selections->getPreviousPaidWithPreviousCycleId(
                        $user->user_id, 
                        $sub->id,
                        $this->previousCycleId
                    );
                    $data[] = $user->user_id;
                    $status = new SubscriptionsCycleStatus($selections->cycle_subscription_status ?? '');
                    if ($status->isPaid()) {
                        if (!in_array($user->user_id, $contacts)) {
                            array_push($contacts, $user->user_id);
                        }
                    }
                }
            }
            
            $contacts = array_unique($contacts);
            foreach($contacts as $userId) {
                if ($this->isSendLastDeliveryDetailsLog($this->deliveryTimingId, $userId)) {
                    $infusionsoftCustomer = new InfusionsoftCustomer($userId);
                    $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
                    $infusionsoftCustomer->updateCustomerInfs();
                    $this->logDeliveryDetailsReport($this->deliveryTimingId, $userId);
                }
            }
        });
    }

    private function isSendLastDeliveryDetailsLog($deliveryTimingsId, $userId)
    {   
        $log = new Log('report_log','logs/'.self::$deliveryDetailsLogFilename.'.log');
        if (!file_exists(storage_path().'/logs/'.self::$deliveryDetailsLogFilename.'.log')) {
            $log->info('Report Log Created. | |');
        }

        $file = file(storage_path().'/logs/'.self::$deliveryDetailsLogFilename.'.log');
        
        foreach(array_reverse($file) as $record) {

            $record = explode('|', $record);

            if (count($record) < 2) continue;

            $deliveryTimingsIdLine = explode(' ', $record[0])[3];
            $referenceId =  explode(' ', $record[2])[0];
            $cycleIdLine =  explode(' ', $record[1])[0];
            
            if ($deliveryTimingsId == $deliveryTimingsIdLine) {
                if ($this->currentCycleId == $cycleIdLine) {
                    if ($userId == $referenceId) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function logDeliveryDetailsReport($deliveryTimingsId, $userId)
    {
        $log = new Log('report_log','logs/'.self::$deliveryDetailsLogFilename.'.log');
        $log->info($deliveryTimingsId.'|'.$this->currentCycleId.'|'.$userId);
    }

}
