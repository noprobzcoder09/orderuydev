<?php

namespace App\Services\Cutover\Cycle;

use  App\Services\Cutover\Dto\Subscriptions as SubscriptionsDto;
use App\Services\Cutover\Dto\SubscriptionsStatus as SubscriptionsStatusDto;
use App\Services\Cutover\Dto\SubscriptionsSelections as SubscriptionsSelectionsDto;
use App\Services\Cutover\Dto\SubscriptionsCycleStatus as SubscriptionsCycleStatusDto;
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

Class ResyncCustomerActiveMenu
{   
    use SetNewMenuSelectionsProvider;

    const PENDING_STATUS = 'pending';
    private $mealPlansDefaultMenu = array();

    public function __construct(int $currentCycleId ,int $deliveryTimingId, \DateTime $deliveryDate)
    {   
        $this->currentCycleId = $currentCycleId;
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
        // Provide default selections menu
        $this->setNewMenuSelectionsProvider();

        $this->user->getUsers()->chunk(100, function($users)
        {   
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

                    $selections = $this->selections->getCurrentWithCurrentCycleId(
                        $user->user_id, 
                        $sub->id,
                        $this->currentCycleId
                    );
                    
                    $selections = new SubscriptionsSelectionsDto(
                        $selections->id ?? 0,
                        $selections->cycle_id ?? 0,
                        $selections->cycle_subscription_status ?? ''
                    );

                    $selectionStatus = new SubscriptionsCycleStatusDto($selections->getStatus());

                    $subscriptions = new SubscriptionsDto(
                        $sub->id,
                        $selections->getId(),
                        $sub->meal_plans_id,
                        $sub->status
                    );

                    if ($selectionStatus->isCancelled()) {
                        continue; // escape if cancelled
                    }
                    
                    if (empty($selections->getId())) {
                        continue; // escape no cycles
                    }
                    
                    if ($selectionStatus->isPaid()) {
                        continue; // do not update the paid subscriptions
                    }

                    echo $this->currentCycleId .'!='. $selections->getCycleId().'<br>';
                    if ($this->currentCycleId != $selections->getCycleId())  {
                        continue; // do not update selections if did not match the current cycle
                    }

                    if (!$this->selections->isStored($user->user_id, $subscriptions->getSubscriptionId(), $this->currentCycleId)) 
                    {
                        $this->selections->copyAndCreate(
                            $selections->getId(), 
                            $this->currentCycleId, 
                            $this->mealPlansDefaultMenu[$subscriptions->getMealPlansId()] ?? '',
                            self::PENDING_STATUS
                        );
                    } else {
                        $this->selections->updateMenuSelections(
                            $selections->getId(), 
                            $this->mealPlansDefaultMenu[$subscriptions->getMealPlansId()] ?? ''
                        );
                    }

                    $toSyncCustomers[$user->user_id] = $user->user_id;
                }
            }

            foreach($toSyncCustomers as $toSyncUserId) {
                $this->log->info('Re sync customer active menu '.$toSyncUserId);
                $infusionsoftCustomer = new InfusionsoftCustomer($toSyncUserId);
                $infusionsoftCustomer->updateCustomerInfs();
            }
        });
    }

}