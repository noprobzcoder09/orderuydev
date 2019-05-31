<?php

namespace App\Services\Customers\BillingIssue;

use App\Models\UserDetails;
use App\Services\Customers\Account\Billing\UnpaidBilling;
use DataTables;
use DB;

Class Data
{   
    const billingIssueStatus = 'billing issue';

    public function __construct()
    {
        $this->details = new UserDetails;
    }

    public function data()
    {   
        return
        $this->details->gerSubscriptions()
        ->addSelect([
            'email',
            'subscriptions.billing_attempt',
            'subscriptions.billing_attempt_desc',            
            'subscriptions_cycles.user_id',
            'subscription_id',
            DB::raw("'0' as weeks_active"),
            DB::raw("
                (select GROUP_CONCAT(DISTINCT plan_name) from subscriptions 
                INNER JOIN meal_plans 
                ON meal_plans.id = subscriptions.meal_plans_id
                INNER JOIN subscriptions_cycles
                ON subscriptions_cycles.subscription_id=subscriptions.id
                where subscriptions.user_id=user_details.user_id
                and subscriptions_cycles.cycle_subscription_status = 'unpaid'
                group by subscriptions.user_id) as plan_name")
        ])
        ->join('users','users.id','=','user_details.user_id')
        ->where('user_details.status','billing issue')
        ->where('user_details.status','<>','cancelled')
        ->groupBy('user_details.user_id');
    }

    public function get()
    {
        return DataTables::of($this->data()->get())->make(true);
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

}


