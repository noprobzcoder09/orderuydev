<?php

namespace App\Services\Cutover\Data;

use App\Models\Subscriptions as Model;
use DB;

Class Subscriptions
{   
    const ACTIVE_STATUS = 'active';
    const BILLING_ISSUE_STATUS = 'billing issue';
    public function __construct()
    {
        $this->subscription = new Model;
    }

    public function getByUser(int $userId)
    {
        return $this->subscription
        ->where('user_id', $userId)
            ->get();
    }

    public function getSubscriptionUsers()
    {   
        $base = $this->base();
        return $this->applyUserFields($base)
        ->groupBy('subscriptions.user_id')
        ->orderBy('subscriptions.id','asc');
    }

    public function getPending(int $userId, int $deliveryTimingId, $previousCycleId)
    {   
        $base = $this->base();
        return $this->applyFields($base)
        ->where('subscriptions.user_id', $userId)
        ->where('cycles.delivery_timings_id', $deliveryTimingId)
        ->where('subscriptions_cycles.cycle_id', $previousCycleId)
        ->groupBy('subscriptions.id')
        ->orderBy('subscriptions.id','asc')
            ->get();  
    }

    public function setStatus(int $id, string $status)
    {
        $model = $this->subscription->find($id);
        $model->status = $status;
        if (in_array(strtolower($status), [self::ACTIVE_STATUS, self::BILLING_ISSUE_STATUS])) {
            $model->paused_till = null;
        }
        $model->save();
    }

    public function removePauseDate(int $id)
    {
        $model = $this->subscription->find($id);
        $model->paused_till = null;
        $model->save();
    }
    
    private function applyUserFields(&$model)
    {
        return $model->select([
            'subscriptions.user_id'
        ]);
    }

    private function applyFields(&$model)
    {
        return $model->select([
            'subscriptions.id','subscriptions.status'
        ]);
    }

    private function base()
    {   
        return $this->subscription
         ->join('subscriptions_cycles',
            'subscriptions.id','=','subscriptions_cycles.subscription_id'
        )
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id');
    }
}
