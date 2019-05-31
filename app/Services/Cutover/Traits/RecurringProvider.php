<?php

namespace App\Services\Cutover\Traits;

use App\Services\Cutover\Billing\Manager as BillingManager;
use App\Services\Customers\Account\Billing\DeliveryBilling;
use App\Services\Cutover\Dto\SubscriptionsBilling as SubscriptionsBillingDto;
use App\Services\Cutover\Dto\UserDetails as UserDetailsDto;
use App\Services\Cutover\Dto\Product as ProductDto;

Trait RecurringProvider
{   
    public function recurringProvider()
    {
        $subscriptions = array();
        
        echo "<pre>";
        $data = array(
            'Previous Cycle Id' => $this->previousCycleId,
            'Current Cycle Id' => $this->currentCycleId
        );
        print_r($data);
        $this->user->getUsersNotCancelled()->chunk(100, function($unCancelledUsers) {
            foreach($unCancelledUsers as $row) 
            {
                $deliveryBilling = new DeliveryBilling(
                    $row->user_id, 
                    $this->deliveryTimingId, 
                    $this->previousCycleId,
                    new \DateTime($this->deliveryDate),
                    new \DateTime($this->currentDeliveryDate)
                );
                
                $userDetails = $deliveryBilling->getUserDetails();

                if (empty($userDetails) || empty($deliveryBilling->getSubscriptions())) {
                    continue; //exclude customer that does not have subscriptions
                }
                // To make sure that for billing subscriptions should have a new current week meals
                // because the updateStatus function is requiring it either failed billing or not
                $this->createNewSubscriptionCycle($row->user_id);
                
                // Resume paused subscriptions that would be active for the next cutover
                $this->resumeSubscriptions(
                    $deliveryBilling->getForResumeSubscriptions()
                );

                $subscriptionsBilling = new SubscriptionsBillingDto(
                    $deliveryBilling->getProducts(), 
                    $deliveryBilling->getSubscriptions(), 
                    $deliveryBilling->getTotal()
                );

                // Skip recharge invoice when the tota discount
                // is greater than the total amount
                $skippedChargeInvoice = false;
                if (
                    ($deliveryBilling->getTotalDiscount() > 0)
                    && ($deliveryBilling->getTotalDiscount() >= $deliveryBilling->getTotal())
                ) {
                    $skippedChargeInvoice = true;
                    $this->log->info('User #'.$row->user_id.' Charge invoice was skipped due to discount is greater than or equal to the total billing amount.');
                }

                $billingManager = new BillingManager(
                    $userDetails,
                    $subscriptionsBilling,
                    $skippedChargeInvoice
                );
                
                $billingManager->handle();

                if ($billingManager->isSuccess()) {
                    array_push($this->successBillingStorage, $row->user_id);
                }
            }
        });
    }
}
