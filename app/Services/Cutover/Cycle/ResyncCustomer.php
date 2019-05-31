<?php

namespace App\Services\Cutover\Cycle;

use  App\Services\Cutover\Dto\Subscriptions as SubscriptionsDto;
use App\Services\Cutover\Dto\SubscriptionsStatus as SubscriptionsStatusDto;
use App\Services\Cutover\Dto\SubscriptionsSelections as SubscriptionsSelectionsDto;
use App\Services\Cutover\Dto\SubscriptionsCycleStatus;
use App\Services\Cutover\Data\Subscriptions;
use App\Services\Cutover\Data\SubscriptionsSelections;

use App\Services\Cutover\Data\User;

use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\InfusionsoftV2\Tag;
use App\Services\Log;

use App\Services\Cutover\Traits\SetNewMenuSelectionsProvider;

use App\Repository\ProductPlanRepository;
use App\Repository\MealsRepository;
use App\Services\Cutover\Data\Cycle;

Class ResyncCustomer
{   
    use SetNewMenuSelectionsProvider;

    const PENDING_STATUS = 'pending';

    public function __construct(int $currentCycleId, int $previousCycleId ,int $deliveryTimingId, \DateTime $deliveryDate)
    {   
        $this->currentCycleId = $currentCycleId;
        $this->previousCycleId = $previousCycleId;
        $this->deliveryTimingId = $deliveryTimingId;
        $this->deliveryDate = $deliveryDate->format('Y-m-d');
        $this->user = new User;
        $this->cycle = new Cycle;

        $this->subscriptions = new Subscriptions;
        $this->selections = new SubscriptionsSelections;
        $this->mealPlansRepository = new ProductPlanRepository;
        $this->mealsRepository = new MealsRepository;

        $this->log = new Log('cutover','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

    public function handle()
    {   
        $this->user->getUsers()->chunk(100, function($users)
        {   
            $contacts = array();
            $toSyncCustomers = array();
            foreach($users as $user)
            {
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

                    $peviousPaidselections = $this->selections->getPreviousPaidWithPreviousCycleId(
                        $user->user_id, 
                        $sub->id,
                        $this->previousCycleId
                    );
                    
                    $selections = new SubscriptionsSelectionsDto(
                        $selections->id ?? 0,
                        $selections->cycle_id ?? 0,
                        $selections->cycle_subscription_status ?? ''
                    );

                    $peviousPaidselectionsStatus = new SubscriptionsCycleStatus(
                        $peviousPaidselections->cycle_subscription_status ?? ''
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
                    
                    if (!$this->selections->isStored($user->user_id, $selections->getId(), $this->currentCycleId)) 
                    {
                        $this->selections->copyAndCreate(
                            $selections->getId(), 
                            $this->currentCycleId, 
                            $this->mealPlansDefaultMenu[$subscriptions->getMealPlansId()] ?? '',
                            self::PENDING_STATUS
                        );
                    }

                    if ($peviousPaidselectionsStatus->isPaid()) {
                        $contacts[$user->ins_contact_id] = $user->ins_contact_id;
                        $toSyncCustomers[$user->user_id] = $user->user_id;
                    }
                }
            }

            foreach($toSyncCustomers as $toSyncUserId) {
                $this->log->info('Re sync customer '.$toSyncUserId);
                $infusionsoftCustomer = new InfusionsoftCustomer($toSyncUserId);
                $infusionsoftCustomer->updateCustomerInfs();
                $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
            }

            $tag = new Tag;
            $collection = collect($contacts);
            $chunks = $collection->chunk(100);
            foreach($chunks->toArray() as  $row) {
                $infusionsoftCustomer = new InfusionsoftCustomer(0);
                $infusionsoftCustomer->savedTagToContact(
                    $tag->getActiveMenuDeliveryId(), array_keys($row)
                );
            }
        });
    }

}