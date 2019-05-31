<?php

namespace App\Services\Customers\BillingIssue;

use App\Services\Customers\BillingIssue\Data;
use App\Services\Customers\Account\Billing\Data\Subscriptions;
use App\Services\Customers\Account\Billing\Data\SubscriptionsSelections;
use App\Services\Customers\Account\InfusionsoftCustomer;

Class FailedBilling
{   
    private $deliveryDate;

    const failedStatus = 'failed';
    const cancelledStatus = 'cancelled';

    public function __construct(\DateTime $deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
        $this->data = new Data;
        $this->subscription = new Subscriptions;
        $this->subscriptionCycle = new SubscriptionsSelections;
    }

    public function handle()
    {   
        foreach($this->data->getDeliveryTimings() as $row) {

            $this->data->getUnpaidSubscriptionCyclesByTimingIdAndDeliveryDate(
                $row->id,
                $this->deliveryDate
            )
            ->chunk(200, function($subscriptions) {
                foreach($subscriptions as $row) {
                    
                    $this->subscription->updateToCancelled(
                        $row->user_id, $row->subscription_id
                    );
                    $this->subscriptionCycle->updateToFailed(
                        $row->user_id, $row->subscriptions_cycle_id
                    );

                    $infusionsoftCustomer = new InfusionsoftCustomer($row->user_id);
                    $infusionsoftCustomer->updateCustomerInfs();
                }
            });

        }
        
    }
}


