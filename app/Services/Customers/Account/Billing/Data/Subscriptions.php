<?php

namespace App\Services\Customers\Account\Billing\Data;

use App\Models\Subscriptions as Model;

Class Subscriptions
{  
    const BILLING_ISSUE_STATUS = 'billing issue';
    const PAUSED_STATUS = 'paused';
    const CANCELLED_STATUS = 'cancelled';
    const ACTIVE_STATUS = 'active';

    public function __construct()
    {
        $this->model = new Model;
    }

    public function getByUser(int $userId)
    {
        return $this->model
        ->where('user_id', $userId);
    }

    public function getUnpaidByUser(int $userId)
    {
        return $this->model
        ->select([
            'subscriptions.id',
            'subscriptions.price',
            'meal_plans_id',
            'subscriptions.status',
            'paused_till',
            'user_id',
            'meal_plans.plan_name',
            'ins_product_id'
        ])
        ->join('meal_plans','meal_plans.id','subscriptions.meal_plans_id')
        ->where('user_id', $userId)
        ->where('status',self::BILLING_ISSUE_STATUS)
        ->orderBy('subscriptions.id','asc')
        ->get();
    }

    public function get(int $userId)
    {
        return $this->model
        ->select([
            'subscriptions.id',
            'subscriptions.price',
            'meal_plans_id',
            'subscriptions.status',
            'paused_till',
            'user_id',
            'meal_plans.plan_name',
            'ins_product_id'
        ])
        ->join('meal_plans','meal_plans.id','subscriptions.meal_plans_id')
        ->where('user_id', $userId)
        ->whereIn('status', [self::ACTIVE_STATUS, self::PAUSED_STATUS])
        ->orderBy('subscriptions.id','asc')
        ->get();
    }

    public function updateToPaid(int $userId, array $subscriptions)
    {
        $this->model->where([
            'user_id' => $userId
        ])
        ->whereIn('id', $subscriptions)
        ->update([
            'status' => 'active'
        ]);
    }

    public function updateToCancelled(int $userId, int $subscriptionId)
    {
        $this->model->where([
            'user_id' => $userId
        ])
        ->where('id', $subscriptionId)
        ->update([
            'status' => 'cancelled'
        ]);
    }
    
}
