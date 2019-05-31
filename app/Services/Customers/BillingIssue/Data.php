<?php

namespace App\Services\Customers\BillingIssue;

use App\Models\UserDetails;
use App\Services\Customers\Account\Billing\UnpaidBilling;
use DB;

Class Data
{   
    const billingIssueStatus = 'billing issue';

    public function __construct()
    {
        $this->details = new UserDetails;
    }

    public function data($cycleId)
    {   
        return DB::table('user_details')
        ->select([
            'user_details.user_id',
            'subscriptions_cycles.id as subscriptions_cycle_id',
            'subscriptions_cycles.subscription_id',
            'email',
            'mobile_phone',
            'user_details.billing_attempt',
            'user_details.billing_attempt_desc',
            'subscriptions_cycles.user_id',
            DB::raw("concat(user_details.first_name,' ',user_details.last_name) as name"),
            DB::raw("
                (select GROUP_CONCAT(DISTINCT plan_name) from subscriptions 
                INNER JOIN meal_plans 
                ON meal_plans.id = subscriptions.meal_plans_id
                INNER JOIN subscriptions_cycles
                ON subscriptions_cycles.subscription_id=subscriptions.id
                where subscriptions.user_id=user_details.user_id
                and subscriptions_cycles.cycle_subscription_status = 'unpaid'
                and subscriptions_cycles.cycle_id={$cycleId}
                group by subscriptions.user_id) as plan_name")
        ])
        ->join('subscriptions','subscriptions.user_id','=','user_details.user_id')
        ->join('subscriptions_cycles', 'subscriptions_cycles.subscription_id','=','subscriptions.id')
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->join('users','users.id','=','user_details.user_id')
        // ->where('user_details.status',self::billingIssueStatus)
        ->where('subscriptions_cycles.cycle_subscription_status','unpaid')
        ->where('cycles.id',$cycleId)
        ->orderBy('user_details.last_name','asc')
        ->groupBy('user_details.user_id');
    }

    public function get(int $cycleId)
    {
        $data = array();
        $invoice = array();
        $plans = array();
        $subscriptions = array();
        $subscriptionsCycles = array();
        foreach($this->data($cycleId)->get() as $row)
        {   
            $this->unpaid = new UnpaidBilling($row->user_id);

            $subscriptionsCycles[$row->user_id][] = $row->subscriptions_cycle_id;

            $data[$row->user_id] =  (object)array(
                'id'   => $row->subscriptions_cycle_id,
                'subscription_id'   => $row->subscription_id,
                'billing_attempt_desc' => $row->billing_attempt_desc,
                'user_id' => $row->user_id,
                'name' => $row->name,
                'email' => $row->email,
                'mobile_phone' => $row->mobile_phone,
                'plan_name' => $row->plan_name,
                'price' => __('config.currency').number_format($this->unpaid->getTotal(),2),
                'billing_attempt' => $row->billing_attempt ?? 0,
                'weeks_active' => '',
                'subscription_cycle_ids' => json_encode($subscriptionsCycles[$row->user_id])
            );
        }

        return $data;
    }

    public function getCardId(int $userId)
    {
        $user = new \App\Repository\UsersRepository;
        $user->setRow($userId);
        $card = $user->getCardId();
        $default = $user->getCardDefault();

        return empty($default) ? $card[0] : $default;
    }

    public function getContactId(int $userId)
    {
        $user = new \App\Repository\UsersRepository;
        $user->setRow($userId);

        return $user->getContactId();
    }

    public function isUserHasBillingissue(int $userId)
    {
        $user = new \App\Repository\UsersRepository;
        $user->setRow($userId);

        return $user->getStatus() == self::billingIssueStatus;
    }

    public function getPreviousDeliveryCycle()
    {   
        return DB::table('subscriptions_cycles')
        ->select(['cycles.id','cycles.delivery_date'])
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->where('subscriptions_cycles.cycle_subscription_status','unpaid')
        ->orderBy('id','desc')
        ->groupBy('cycles.id')
        ->get();
    }

    public function getUnpaidSubscriptionCyclesByCustomerAndCycleId(int $userId, int $cycleId)
    {
        return DB::table('subscriptions_cycles')
        ->select(['subscriptions_cycles.id','subscriptions_cycles.subscription_id'])
        ->where('subscriptions_cycles.cycle_subscription_status','unpaid')
        ->where('subscriptions_cycles.user_id', $userId)
        ->where('subscriptions_cycles.cycle_id', $cycleId)
        ->get();
    }

    public function getUnpaidSubscriptionCyclesByTimingIdAndDeliveryDate(int $deliveryTimingId, \DateTime $deliveryDate)
    {
        return DB::table('subscriptions_cycles')
        ->select([
            'subscriptions_cycles.user_id',
            'subscriptions_cycles.id as subscriptions_cycle_id',
            'subscriptions_cycles.subscription_id'
        ])
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->where('subscriptions_cycles.cycle_subscription_status','unpaid')
        ->where('cycles.delivery_date','<=',$deliveryDate->format('Y-m-d'))
        ->where('cycles.delivery_timings_id',$deliveryTimingId)
        ->orderBy('subscriptions_cycles.user_id');
    }

    public function getDeliveryTimings()
    {
        return DB::table('delivery_timings')->get();
    }

}


