<?php

namespace App\Services;

use Request;
use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\Validator;
use App\Traits\Auditable;

use App\Services\Dashboard\Dto\SubscriptionsDto;
use App\Services\Dashboard\Dto\CalculateSharedCouponDiscountDto;
use App\Services\Dashboard\Dto\ProductDto;

class Dashboard
{      
    use Auditable;
    /*
        If no previous selection, then retrieve the latest selections
        Todo: Find the cons
        
        If the customer will update the selections and the cutover date is due, what will happen?
        Problem, the cook orders preparation is already fixed and prepared so no longer accept change/cancel order selections
        Solution 1: The saved selection will be deliver on the next delivery (Not for the current cutover).
                    So the current selections will still be deliver
        Solution 2: Updating is disabled if the current cycle cutover is due.
        
        I prefer solution 1
        
        Todo: Store the updated selections in other table together with the cyle id 
        and once the the delivery date is due then the store selections will be transferred to customer subscription selections
        Automate this using cron job
    */

    const billingIssueStatus = 'billing issue';
    
    private $id;

    public function __construct(int $userId, int $planId = null, $subscriptionId = null)
    {
        $this->userId = $userId;
        $this->mealRepository = new \App\Repository\MealsRepository;
        $this->cycleRepository = new \App\Repository\CycleRepository();
        $this->usersRepository = new \App\Repository\UsersRepository();
        $this->subscriptionRepo = new \App\Repository\SubscriptionRepository;
        $this->subscriptionRepoSel = new \App\Repository\SubscriptionSelectionsRepository;
        $this->billingRepo = new \App\Repository\BillingRepository(Request::all());
        $this->customRepo = new \App\Repository\CustomerRepository;
        $this->planRepository = new \App\Repository\ProductPlanRepository;
        $this->zoneRepository = new \App\Repository\ZoneRepository;

        if (!empty($subscriptionId))  {
            $this->subscriptionId = $subscriptionId;
        }

        if (!empty($planId))  {
            $this->planRepository->setId($planId);
        }
        elseif (isset($this->subscription->meal_plans_id) && empty($planId)) {
            $this->planRepository->setId($this->subscription->meal_plans_id);
        }
        $this->validator = new Validator;
    }


    private function isCutoverDue(int $cycleId)
    {   
        $cycle = $this->cycleRepository->get($cycleId);

        $now = new \DateTime(date('Y-m-d'));
        $cutover = new \DateTime($cycle->cutover_date);
        $delivery = new \DateTime($cycle->delivery_date);
        
        if (($cutover <= $now)) {
            return true;
        }
        return false;
    }

    public function getDeliveryZoneTimings(int $zoneId)
    {
        $data = [];
        $model = new \App\Models\DeliveryZone;
        
        foreach($model->timings()->where(['delivery_zone_id' => $zoneId])->get() as $row) {
            $deliverydate = date('l jS F Y', strtotime($row->delivery_date));
            $data[] = [
                'id'    => $row->delivery_zone_timings_id,
                'date'  => $deliverydate
            ];
        }

        return $data;
    }
    
    public function getDeliveryTimings(int $zoneId)
    {
        
        foreach($this->ZTRepository->getTimings($zoneId) as $row) {
            $data[] = [
                'id'    => $row->id,
                'date'  => 'Cutoff Day '.$row->cutoff_day.' @ '
                .date('h:i A', strtotime($row->cutoff_time))
                .' / Delivery Day '.$row->delivery_day
            ];
        }

        return $data;
    }

    public function getNextDateDelivery(int $DZtimingId)
    {
        $data = [];
        $model = new \App\Models\DeliveryZone;
        $activeBatch = (new \Configurations)->getActiveBatch();
        $timingId = $this->usersRepository->getDeliveryTimingId($DZtimingId);
        $cycle = $this->cycleRepository->getByTimingAndBatch($timingId, $activeBatch);
        $activeCycleId = $cycle->id;
        
        if ($this->isCutoverDue($activeCycleId)) {
            $cycle = $this->cycleRepository->getNextBatch($activeBatch);
            $activeBatch = $cycle->batch;
        }
        
        $data = $this->cycleRepository->getByTimingAndBatch($timingId, $activeBatch);
        $deliverydate = date('l jS F Y', strtotime($data->delivery_date));
        
        return $deliverydate;
    }

    public function getPlans()
    {   
        $plans = [];
        $subscriptionsData = [];
        $data = $this->subscriptionRepo
        ->getMyPlansWithSubscriptionCycles($this->userId)
        ->leftJoin('subscriptions_discounts',
            'subscriptions_discounts.id','=','subscriptions_cycles.discount_id'
        )
        ->leftJoin('cycles',
            'subscriptions_cycles.cycle_id','=','cycles.id'
        )
        ->addSelect([
            'paused_till',
            'subscriptions_cycles.id as subscriptions_cycle_id',
            'subscriptions.price',
            'subscriptions_discounts.total_recur_discount',
            'discount_id',
            'meta_data',
            'subscriptions_cycles.cycle_subscription_status as cycle_subscription_status',
            'subscriptions_cycles.ins_invoice_id as ins_invoice_id',
            'no_subscriptions',
            'cycles.delivery_date'
        ])
        ->whereRaw('subscriptions_cycles.cycle_id in (select id from cycles where cycles.status=1)')
        ->get();

        //d($data);

        foreach($data as $row) {
            $product = new ProductDto(
                $row->meal_plans_id,
                $row->ins_product_id,
                (int)env('PRODUCT_ITEMTYPE'),
                $quantity = 1,
                $row->price,
                $row->plan_name
            );
            $meta_data = json_decode($row->meta_data ?? null) ?? [];
            $meta_data = is_array($meta_data) ? $meta_data : (array)$meta_data;
            $subscriptionsData[] = new SubscriptionsDto(
                $row->id, 
                $row->subscriptions_cycle_id,
                $product,
                $row->discount_id,
                $meta_data,
                ''
            );
        }
        
        $discountCalculator = new CalculateSharedCouponDiscountDto($this->userId, $subscriptionsData);

        foreach($data as $row) {
            $discount = $discountCalculator->getTotalDiscount($row->discount_id);
            $numberOfOrders = $discountCalculator->getNumberOfOrders($row->discount_id);
            $discount = $discount > 0 ? $discount/$numberOfOrders : 0;
            $plans[] = [
                'id' => $row->id,
                'planId' => $row->meal_plans_id,
                'name' => $row->plan_name,
                'price' => number_format($row->price - $discount,2),
                'quantity' => $row->quantity,
                'pausedDate' => !empty($row->paused_till) ? date('l jS F Y', strtotime($row->paused_till)) : '',
                'status' => $row->status,
                'vegetarian' => $row->vegetarian ? ' Vegetarian' : '',
                'subscriptionsCycleId' => $row->subscriptions_cycle_id,
                'cycle_subscription_status' => $row->cycle_subscription_status,
                'ins_invoice_id' => $row->ins_invoice_id,
                'delivery_date' => !empty($row->delivery_date) ? date('l jS F Y', strtotime($row->delivery_date)) : '',
            ];
        }
       
        return $plans;
    }

    public function cancellAllPlans()
    {   
        $this->subscriptionRepo->cancellAllPlans($this->userId);
        $this->subscriptionRepoSel->cancellAllPlans($this->userId);

        $cancelled_paused_plans   = $this->subscriptionRepo->getCancelledPausedMealPlan($this->userId);
        // $formatted_date = date('M d, Y', strtotime($paused_plan->paused_till));
        $additional_details = '';

        $cancelled_paused_plans_counter = 0;

        foreach ($cancelled_paused_plans as $cancelled_paused_plan) {

            $cancelled_paused_plans_counter += 1;

            $additional_details .= $cancelled_paused_plan->meal_plan['plan_name'];

            $additional_details.= $cancelled_paused_plans_counter !== $cancelled_paused_plans->count() ? ', ' : '.';
            
        }

        $this->audit('User Cancelled All Subscriptions', 'The user has cancelled all subscriptions.', $additional_details);

        $infusionsoftCustomer = new InfusionsoftCustomer($this->userId);
        $infusionsoftCustomer->updateCustomerInfs();
        $infusionsoftCustomer->cancelledAPlan();

        return 1;
    }

    public function cancellPlan(int $subscribeId, int $subscriptionsCycleId)
    {   
        $this->subscriptionRepo->cancellPlan($this->userId, $subscribeId);
        $this->subscriptionRepoSel->cancellPlan($this->userId, $subscriptionsCycleId);
        
        $cancelled_plan = $this->subscriptionRepo->getMealPlanBySubscriptionId($subscribeId);
        $this->audit('User Cancelled the Subscription', 'The user cancelled the subscription of this ' . $cancelled_plan->meal_plan['plan_name'] . ' plan.', '');
        
        $infusionsoftCustomer = new InfusionsoftCustomer($this->userId);
        $infusionsoftCustomer->updateCustomerInfs();
        $infusionsoftCustomer->cancelledAPlan();

        return 1;
    }
    
    public function saveStopTillDate(int $subscribeId, int $subscriptionsCycleId, \DateTime $date)
    {   
        $now = new \DateTime(date('Y-m-d'));
        if ($now < $date) {

            $this->subscriptionRepo->saveStopTillDate($this->userId, $subscribeId, $date);
            $this->subscriptionRepoSel->saveStopTillDate($this->userId, $subscriptionsCycleId);


            $paused_plan    = $this->subscriptionRepo->getMealPlanBySubscriptionId($subscribeId);
            $formatted_date = date('M d, Y', strtotime($paused_plan->paused_till));
            $this->audit('User Paused Subscription', 'The user paused the '.$paused_plan->meal_plan['plan_name'].' subscription until ' . $formatted_date .'.', '');
            
            $infusionsoftCustomer = new InfusionsoftCustomer($this->userId);
            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->pausedAPlan();
            
            return [
                'success' => true,
                'message' => 'Successfully Saved Pause Date'
            ];
        }
        else {
            return [
                'success' => false,
                'message' => 'Pause date is invalid.'
            ];
        }
    }

    public function saveStopAllTillDate(\DateTime $date)
    {   
        $now = new \DateTime(date('Y-m-d'));
        if ($now < $date) {

            $this->subscriptionRepo->saveStopAllTillDate($this->userId, $date);
            $this->subscriptionRepoSel->saveStopAllTillDate($this->userId);

            $paused_plans   = $this->subscriptionRepo->getNewlyPausedMealPlan($this->userId, $date);
            // $formatted_date = date('M d, Y', strtotime($paused_plan->paused_till));
            $additional_details = '';
            $paused_plans_counter = 0;
            foreach ($paused_plans as $paused_plan) {

                $paused_plans_counter += 1;

                $additional_details .= $paused_plan->meal_plan['plan_name']. ' ('.date('M d, Y', strtotime($paused_plan->paused_till)).')';

                $additional_details.= $paused_plans_counter !== $paused_plans->count() ? ', ' : '.';
                
            }

            $this->audit('User Paused All Subscriptions', 'The user paused all of his/her subscriptions.', $additional_details);

            $infusionsoftCustomer = new InfusionsoftCustomer($this->userId);
            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->pausedAPlan();
            
            return [
                'success' => true,
                'message' => 'Successfully Saved Pause Date'
            ];
        }
        else {
            return [
                'success' => false,
                'message' => 'Pause date is invalid.'
            ];
        }
    }
    

    public function cancelPausedDate(int $subscribeId, int $subscriptionsCycleId)
    {
        $out = $this->subscriptionRepo->cancelPausedDate($this->userId, $subscribeId);
        $this->subscriptionRepoSel->cancelPausedDate($this->userId, $subscriptionsCycleId);

        $cancelled_paused_plan    = $this->subscriptionRepo->getMealPlanBySubscriptionId($subscribeId);
        $this->audit('User Cancelled the Paused Subscription', 'The user cancelled the paused subscription of this meal plan '.$cancelled_paused_plan->meal_plan['plan_name'].'.', '');

        $infusionsoftCustomer = new InfusionsoftCustomer($this->userId);
        $infusionsoftCustomer->updateCustomerInfs();

        return $out;
    }

    public function getCardInfo()
    {
        return [
            'name' => $this->billingRepo->getPostCardName(),
            'number' => $this->billingRepo->getPostCardNumber(),
            'expirationMonth' => $this->billingRepo->getPostExpMonth(),
            'expirationYear' => $this->billingRepo->getPostExpYear(),
            'cvc' => $this->billingRepo->getPostCardCVC()
        ];
    }

    public function updateInfoAddress(array $data)
    {    
        $data['name'] = $data['first_name'].' '.$data['last_name'];
        $this->billingRepo->update($data);

        if (!empty($this->userId)) 
        {
            if ($this->billingRepo->hasDetails($this->userId)) {
                $this->billingRepo->updateDetails($this->userId, $data);
            } else {
                $this->billingRepo->storeDetails($this->userId, $data);
            }

            if ($this->billingRepo->hasAddres($this->userId)) {
                $this->billingRepo->updateAddress($this->userId, $data);
            } else {
                $this->billingRepo->storeAddress($this->userId, $data);
            }

        }
    }

    public function updateBillingInfo(array $data)
    {   
        $data['id'] = $this->userId;
        $response['success'] = false;
        $this->validator->validate($data, $this->billingRepo->updateInfoAddressRules());

        if (!$this->validator->isValid) {
            $response['message'] = $this->validator->filterError($this->validator->messages);
            return $response;
        }

        $this->updateInfoAddress($data);

        $this->audit('User Update Billing Details', 'The user updated his/her billing details.', '');
        // After uppdating the user profile database
        // Reterive the records and insert here
        // Update to contact information
        $infusionsoft = new InfusionsoftCustomer($this->userId);
        $infusionsoft->updateCustomerInfs();

        $response['message'] = $this->billingRepo->successSavedInfoAddressMessage;
        $response['success'] = true;

        return $response;
    }
    
    public function myPlansIdOnly()
    {
        $plans = [];
        foreach($this->subscriptionRepo->getMyPlans($this->userId)->get() as $row) {
            $plans[] = $row->meal_plans_id;
        }
        return $plans;
    }

    public function getSubscriptionIds()
    {
        $ids = [];
        $data = $this->subscriptionRepo
        ->getMyPlansWithSubscriptionCycles($this->userId)
         ->addSelect([
            'subscriptions_cycles.id as subscriptions_cycle_id'
        ])
        ->whereRaw('subscriptions_cycles.cycle_id in (select id from cycles where status=1)')
        ->get();
        foreach($data as $row) {
            $ids[] = [
                'subscription_id' => $row->id,
                'subscriptions_cycle_id' => $row->subscriptions_cycle_id,
            ];
        }
        return $ids;
    }
    

    public function myPlans()
    {
        return $this->subscriptionRepo->getMyPlans($this->userId)->get();
    }

    public function mealPlans()
    {
        return $this->planRepository->getAll();
    }
    
    public function iSubscribed()
    {
        $this->subscriptionSel = $this->subscriptionRepoSel->getPreviousSelections($this->userId);
        return isset($this->subscriptionSel->subscription_id) ? true : false;
    }

    public function getFutureDeliveryTimingSchedule(int $subscriptionCycleId)
    {
        $cycleId = $this->subscriptionRepoSel->getCycleIdById($subscriptionCycleId);
        $cycle = $this->cycleRepository->get($cycleId);

        $deliveryDate = $cycle->delivery_date;

        for($i = 0; $i < 20; $i++) {
            $deliveryDate = date('Y-m-d', strtotime($deliveryDate.' +1 week'));
            // This is unecessary and for testing purpose only
            $data[] = $deliveryDate;
        }
        return $data;
    }

    public function isUserHasBillingissue(int $userId)
    {
        $this->usersRepository->setRow($userId);

        return $this->usersRepository->getStatus() == self::billingIssueStatus;
    }

    public function getUnpaidSubscriptionsToArray(int $userId)
    {
        $data = array();
        foreach($this->customRepo->getUnpaidSubscriptions($userId) as $row) {
            array_push($data, (object)array(
                'delivery_date' => date('l jS F Y', strtotime($row->delivery_date)),
                'plan_name' => $row->plan_name,
                'subscription_id' => $row->subscription_id,
                'subscriptions_cycle_id' => $row->subscriptions_cycle_id,
                'subscription_id' => $row->subscription_id,
                'cycle_subscription_status' => $row->cycle_subscription_status
            ));
        }
        return $data;
    }

    public function getForDeliverySubscriptionsToArray(int $userId)
    {
        $data = array();
        foreach($this->customRepo->getForDeliverySubscriptions($userId) as $row) {
            array_push($data, (object)array(
                'delivery_date' => date('l jS F Y', strtotime($row->delivery_date)),
                'plan_name' => $row->plan_name,
                'subscription_id' => $row->subscription_id,
                'subscriptions_cycle_id' => $row->subscriptions_cycle_id,
                'subscription_id' => $row->subscription_id
            ));
        }
        return $data;
    }

    public function isUserDeliveryZoneRemoved($userId)
    {
        $deliveryZoneId = $this->usersRepository->getDeliveryZoneId(
            $this->usersRepository->getDeliveryZoneTimingId($userId)
        );
        
        return $this->zoneRepository->empty($deliveryZoneId);
    }

    
    public function getDeliveryTimingsSettings(int $userId)
    {
        return $this->usersRepository->getDeliveryZoneTimingByUserId((int)$userId);
    } 
}

