<?php

namespace App\Services\Dashboard;

use App\Services\Dashboard\Extended\Request;
use App\Services\Dashboard\Extended\User;
use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Repository\SubscriptionSelectionsRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\CustomerRepository;
use App\Services\Customer;
use App\Traits\Auditable;

class Subscription
{      
    use Auditable;

    public function __construct(int $userId)
    {
        $this->id = $userId;
        $this->user = new User($userId);
        $this->request = new Request;

        $this->subscriptionCycleRepo = new SubscriptionSelectionsRepository;
        $this->subscriptionRepo = new SubscriptionRepository;
        $this->customerRepo = new CustomerRepository;
        $this->customer = new Customer($this->customerRepo);
    }

    public function cancelSubscriptionCycle(int $subscriptionId, int $subscriptionCycleId)
    {
        try 
        {   
            $this->subscriptionCycleRepo
            ->cancellPlan(
                $this->id, 
                $subscriptionCycleId
            );

            $this->subscriptionRepo
            ->activatePlan(
                $this->id, 
                $subscriptionId
            );
            
            $infusionsoftCustomer = new InfusionsoftCustomer($this->id, 'inline');
            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();

            $description        = $this->user->getBillName() . ' cancelled subscription cycle.';
            $additional_data    = 'Customer Process: ';
            $additional_data    .= "(Cancel Subscription Cycle: {$subscriptionCycleId}) -> (Activate Subscription: {$subscriptionId})";
            $this->audit($title = 'Customer Cancelled Subscription Cycle', $description, $additional_data, $user_id = $this->id);

            $infusionsoftCustomer->cancelledWeek();
            
            return ['success' => true, 'message' => sprintf(__('crud.cancelled'),'subscription')];
        }
        catch (\Exception $e) {
            throw $e;
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function cancelSubscription(int $subscriptionId, int $subscriptionCycleId)
    {
        try 
        {   
            $this->subscriptionRepo
            ->cancellPlan(
                $this->id, 
                $subscriptionId
            );

            $this->subscriptionCycleRepo
            ->cancellPlan(
                $this->id, 
                $subscriptionCycleId
            );

            $subscriptions = $this->subscriptionRepo->getCancelledPlans($this->id);

            $plans = [];
            foreach($subscriptions->get() as $row) {
                $plans[] = $row->plan_name;
            }

            $infusionsoftCustomer = new InfusionsoftCustomer($this->id, 'inline');
            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();
            $infusionsoftCustomer->updatePausedCancelledPlans(implode(',', $plans));
            $infusionsoftCustomer->cancelledWeek();

            $description        = $this->user->getBillName() . ' cancelled subscription.';
            $additional_data    = 'Customer Process: ';
            $additional_data    .= " (Cancel Subscription: {$subscriptionId}) -> (Cancel Subscription Cycle: {$subscriptionCycleId})";
            $this->audit($title = 'Customer Cancelled Subscription', $description, $additional_data, $user_id = $this->id);

            return ['success' => true, 'message' => sprintf(__('crud.cancelled'),'subscription')];
        }
        catch (\Exception $e) {
            throw $e;
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function isCancelledLastWeek()
    {
        return $this->customer->isCancelledLastWeek($this->id);
    }
    
}

