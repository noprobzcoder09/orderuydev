<?php

namespace App\Services\Cutover\Traits;

use App\Services\Cutover\Billing\Consolidate;
use App\Services\Cutover\Billing\Discount;
use App\Services\Cutover\Formatter\Merge;
use App\Services\Cutover\Billing\Bill;

use  App\Services\Cutover\Dto\SubscriptionsStatus as SubscriptionsStatusDto;
use  App\Services\Cutover\Dto\Subscriptions as SubscriptionsDto;
use  App\Services\Cutover\Dto\SubscriptionsSelections as SubscriptionsSelectionsDto;

use App\Services\Customers\Account\InfusionsoftCustomer;

Trait CreateNewSubscriptionCycleProvider
{   
    private function createNewSubscriptionCycle(int $userId)
    {   
        foreach($this->subscriptions->getByUser($userId) as $sub)
        {   
            $status = new SubscriptionsStatusDto(
                $sub->status,
                new \DateTime($sub->paused_till),
                new \DateTime($this->deliveryDate)
            );
            
            if ($status->isCancelled()) continue;

            $selections = $this->selections->getCurrentWithTimingId(
                $userId, 
                $sub->id,
                $this->deliveryTimingId
            );

            $selections = new SubscriptionsSelectionsDto(
                $selections->id ?? 0,
                $selections->cycle_id ?? 0,
                $selections->cycle_subscription_status ?? ''
            );

            $subscriptions = new SubscriptionsDto(
                $sub->id,
                $selections->getId(),
                $sub->meal_plans_id,
                $sub->status
            );
            
            if (empty($selections->getId())) {
                continue; // escape no cycles
            }

            $this->log->info($userId.'/'.$selections->getCycleId() .'=='. $this->currentCycleId);
            if ($selections->getCycleId() == $this->currentCycleId) {
                continue; // escape the current week.
            }

            if ($this->selections->isStored($userId, $sub->id, $this->currentCycleId)) {
                continue; // escape previously added week.
            }

            $this->copyAndCreateSubscriptionSelections(
                $selections->getId(),
                $this->currentCycleId,
                $this->mealPlansDefaultMenu[$subscriptions->getMealPlansId()] ?? '',
                $status->isPaused() ? self::PAUSED_STATUS : self::PENDING_STATUS
            );
        }
    }

    private function createNewCycleForActiveSubscription()
    {   
        $this->user->getUsersNotCancelled()->chunk(100, function($users)
        {   
            $contacts = array();
            foreach($users as $user)
            {   
                $skipSyncCustomer = true;
                foreach($this->subscriptions->getByUser($user->user_id) as $sub)
                {
                    $status = new SubscriptionsStatusDto(
                        $sub->status,
                        new \DateTime($sub->paused_till),
                        new \DateTime($this->deliveryDate)
                    );
                    
                    if ($status->isCancelled()) continue;

                    $selections = $this->selections->getCurrentWithTimingId(
                        $user->user_id, 
                        $sub->id,
                        $this->deliveryTimingId
                    );

                    $selections = new SubscriptionsSelectionsDto(
                        $selections->id ?? 0,
                        $selections->cycle_id ?? 0,
                        $selections->cycle_subscription_status ?? ''
                    );

                    $subscriptions = new SubscriptionsDto(
                        $sub->id,
                        $selections->getId(),
                        $sub->meal_plans_id,
                        $sub->status
                    );
                    
                    if (empty($selections->getId())) {
                        continue; // escape no cycles
                    }

                    if ($selections->getCycleId() == $this->currentCycleId) {
                        continue; // escape the current week.
                    }

                    if ($this->selections->isStored($user->user_id, $sub->id, $this->currentCycleId)) {
                        continue; // escape previously added week.
                    }

                    $this->copyAndCreateSubscriptionSelections(
                        $selections->getId(),
                        $this->currentCycleId,
                        $this->mealPlansDefaultMenu[$subscriptions->getMealPlansId()] ?? '',
                        $status->isPaused() ? self::PAUSED_STATUS : self::PENDING_STATUS
                    );

                    $skipSyncCustomer = false;
                }

                if ($skipSyncCustomer) {
                    continue; //skip customers that don't have selections from last week
                }

                if (!in_array($user->user_id, $this->successBillingStorage)) {
                    array_push($contacts, $user->user_id);
                }
            }

            $contacts = array_unique($contacts);
            foreach($contacts as $userId) {
                $this->log->info('Create new cycle for this user '.$userId);
                $infusionsoftCustomer = new InfusionsoftCustomer($userId);
                $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
                $infusionsoftCustomer->updateCustomerInfs();
            }
        });
    }
}
