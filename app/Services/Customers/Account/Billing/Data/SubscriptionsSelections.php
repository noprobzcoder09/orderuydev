<?php

namespace App\Services\Customers\Account\Billing\Data;

use App\Models\SubscriptionsSelections as Model;

Class SubscriptionsSelections
{  
    const PENDING_STATUS = 'pending';
    const PAUSED_STATUS = 'paused';
    const FAILED_STATUS = 'failed';
    const REFUNDED_STATUS = 'refunded';
    const PAID_STATUS = 'paid';
    const CANCELLED_STATUS = 'cancelled';
    const UNPAID_STATUS = 'unpaid';

    public function __construct()
    {
        $this->model = new Model;
    }

    public function getPendingWithPreviousCycleId(int $userId, int $subscriptionId,int $previousCycleId)
    {
        return $this->model
        ->leftJoin('subscriptions_discounts',
            'subscriptions_discounts.id','=',
            'subscriptions_cycles.discount_id'
        )
        ->select([
            'subscriptions_discounts.meta_data','subscriptions_cycles.id',
            'subscriptions_cycles.cycle_id',
            'subscriptions_cycles.subscription_id',
            'subscriptions_cycles.cycle_subscription_status',
            'subscriptions_cycles.discount_id'
        ])
        ->where([
            'subscriptions_cycles.user_id' => $userId,
            'subscription_id' => $subscriptionId,
            'subscriptions_cycles.cycle_id' => $previousCycleId
        ])
        ->whereIn('cycle_subscription_status', [
            self::PENDING_STATUS, self::PAUSED_STATUS
        ])
        ->orderBy('id','desc')
        ->first();
    }

    public function getCurrent(int $userId, int $subscriptionId)
    {
        return $this->model->where([
            'subscriptions_cycles.user_id' => $userId,
            'subscription_id' => $subscriptionId
        ])
        ->orderBy('id','desc')
        ->limit(1)
        ->get();
    }

    public function getUnpaid(int $userId, int $subscriptionId)
    {
        return $this->model->where([
            'subscriptions_cycles.user_id' => $userId,
            'subscription_id' => $subscriptionId,
            'cycle_subscription_status' => self::UNPAID_STATUS
        ])
        ->leftJoin('subscriptions_discounts',
            'subscriptions_discounts.id','=',
            'subscriptions_cycles.discount_id'
        )
        ->leftJoin('subscriptions_invoice',
            'subscriptions_invoice.ins_invoice_id','=',
            'subscriptions_cycles.ins_invoice_id'
        )
        ->orderBy('id','desc')
        ->select([
            'subscriptions_discounts.meta_data','subscriptions_cycles.id',
            'subscriptions_cycles.cycle_id',
            'subscriptions_cycles.subscription_id',
            'subscriptions_cycles.cycle_subscription_status',
            'subscriptions_cycles.discount_id'
        ])
        ->first();
    }

    public function getInvoice(int $userId, int $subscriptionId, int $previousCycleId)
    {
        return $this->model->where([
            'subscriptions_cycles.user_id' => $userId,
            'subscription_id' => $subscriptionId
        ])
        ->whereNotIn('cycle_subscription_status',[
            self::FAILED_STATUS, 
            self::REFUNDED_STATUS, 
            self::CANCELLED_STATUS
        ])
        ->where('cycle_id','<',$previousCycleId)
        ->orderBy('id','desc')
        ->select([
            'subscriptions_cycles.ins_invoice_id',
            'subscriptions_cycles.cycle_subscription_status'
        ])
        ->limit(1)
        ->get();
    }

    public function updateToPaid(int $userId, $invoiceId, array $subscriptions)
    {   
        foreach($subscriptions as $id) {

            $this->model->where([
                'subscription_id' => $id,
                'user_id' => $userId,
                'cycle_subscription_status' => 'unpaid'
            ])
            ->update([
                'ins_invoice_id' => $invoiceId,
                'cycle_subscription_status' => 'paid'
            ]);
        }
    }

    public function cancelForTheWeek(int $userId, array $subscriptionCycleIds)
    {   
        foreach($subscriptionCycleIds as $id) 
        {
            $model = $this->model->findOrFail($id);

            $model->cycle_subscription_status = 'cancelled';
            $model->save();
        }
    }

    public function updateToFailed(int $userId, int $subscriptionId)
    {
        $this->model->where([
            'user_id' => $userId
        ])
        ->where('id', $subscriptionId)
        ->update([
            'cycle_subscription_status' => 'failed'
        ]);
    }
    
}
