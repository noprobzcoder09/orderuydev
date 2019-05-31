<?php

namespace App\Services\Cutover\Data;

use App\Models\SubscriptionsSelections as Model;
use DB;

Class SubscriptionsSelections
{   
    public $billingAttemptMax = 5;
    const PAID_STATUS = 'paid';
    public function __construct()
    {
        $this->selection = new Model;
    }

     public function getPending(int $subscriptionId, int $previousCycleId)
    {   
        $base = $this->base();
        return $this->applyFields($base)
        ->whereIn('subscriptions_cycles.cycle_subscription_status',[
            'pending'
        ])
        ->orderBy('subscriptions_cycles.id','desc')
        ->where('subscriptions_cycles.subscription_id', $subscriptionId)
        ->limit(1)
        ->get();
    }

    public function getCurrentWithTimingId(int $userId, int $subscriptionId, int $deliveryTimingsId)
    {
        return $this->selection->where([
            'subscriptions_cycles.user_id' => $userId,
            'subscription_id' => $subscriptionId,
            'cycles.delivery_timings_id' => $deliveryTimingsId
        ])
        ->select([
            'subscriptions_cycles.id',
            'cycle_subscription_status',
            'subscriptions_cycles.cycle_id'
        ])
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->join('delivery_timings','delivery_timings.id','=','cycles.delivery_timings_id')
        ->orderBy('subscriptions_cycles.id','desc')
        ->limit(1)
        ->first();
    }

    public function getCurrentWithCurrentCycleId(int $userId, int $subscriptionId, int $currentCycleId)
    {
        return $this->selection->where([
            'subscriptions_cycles.user_id' => $userId,
            'subscription_id' => $subscriptionId,
            'subscriptions_cycles.cycle_id' => $currentCycleId
        ])
        ->select([
            'subscriptions_cycles.id',
            'cycle_subscription_status',
            'subscriptions_cycles.cycle_id'
        ])
        ->orderBy('subscriptions_cycles.id','desc')
        ->limit(1)
        ->first();
    }

    public function getPreviousPaidWithPreviousCycleId(int $userId, int $subscriptionId, int $previousCycleId)
    {
        return $this->selection->where([
            'subscriptions_cycles.user_id' => $userId,
            'subscription_id' => $subscriptionId,
            'subscriptions_cycles.cycle_id' => $previousCycleId,
            'cycle_subscription_status' => self::PAID_STATUS
        ])
        ->select([
            'subscriptions_cycles.id',
            'cycle_subscription_status',
            'subscriptions_cycles.cycle_id'
        ])
        ->orderBy('subscriptions_cycles.id','desc')
        ->limit(1)
        ->first();
    }

    public function isStored(int $userId, int $subscriptionId, int $cycleId)
    {
        return $this->selection->where([
            'user_id' => $userId,
            'subscription_id' => $subscriptionId,
            'cycle_id' => $cycleId
        ])
        ->count() > 0;        
    }

    public function getSubscriptionCyclePreviousInvoiceId(int $subscriptionId)
    {
        $id = $this->selection
        ->where('subscription_id', $subscriptionId)
        ->whereIn('cycle_subscription_status',['old week','paid'])
        ->orderBy('id','desc')
        ->first();

        return $id->ins_invoice_id ?? 0;
    }

    public function updateInvoice(int $subcycleId, $invoiceId)
    {   
        return $this->selection
        ->where(['id' => $subcycleId])
        ->update(['ins_invoice_id' => $invoiceId]);
    }

    public function setStatus(int $subcycleid, string $status)
    {
        $model = $this->selection->find($subcycleid);

        $model->cycle_subscription_status = $status;
        $model->save();
    }

    public function setStatusWithAttempt(int $subcycleid, string $status, string $message)
    {
        $model = $this->selection->find($subcycleid);
        
        $model->cycle_subscription_status = $status;
        $model->save();
    }

    public function copyAndCreate(int $subcycleid, int $cycleId, string $menuSelections, string $status)
    {   
        $model = $this->selection->find($subcycleid);
        $modelCycleId = $model->cycle_id ?? 0;
        if ($cycleId == $modelCycleId) {
            return false;
        }

        $model->ins_invoice_id = null;
        $model->cycle_id = $cycleId;
        $model->cycle_subscription_status = $status;
        $model->menu_selections = $menuSelections;
        $model->replicate()->save();
    }

    public function updateMenuSelections(int $subcycleid, string $menuSelections)
    {   
        $model = $this->selection->find($subcycleid);
        
        if (! $model) {
            return false;
        }

        $model->menu_selections = $menuSelections;
        $model->save();
    }

    public function setSubscriptionId(int $id)
    {
        $this->subscriptionId = $id;
    }

    public function getBillingStatus()
    {
        return $this->billingStatus;
    }

     private function applyFields(&$model)
    {
        return $model->select([
            'menu_selections','delivery_timings_id',
            'plan_name','subscriptions.price',
            'ins_contact_id','card_ids','default_card',
            'ins_product_id','paused_till',
            'user_details.user_id','delivery_notes',
            'subscriptions.status','total_recur_discount',
            'subscriptions_cycles.id as subscriptions_cycle_id',
            'cycle_subscription_status','cycle_id','subscription_id',
            'ins_invoice_id','subscriptions_discounts.meta_data',
            'discount_id','meal_plans_id'
        ]);
    }

    private function base()
    {   
        return $this->selection
         ->join('subscriptions',
            'subscriptions.id','=','subscriptions_cycles.subscription_id'
        )
        ->join('user_details',
            'user_details.user_id','=','subscriptions.user_id'
        )
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->join('meal_plans','meal_plans.id','=','subscriptions.meal_plans_id')
        ->leftJoin('subscriptions_discounts',
            'subscriptions_discounts.id',
            '=','subscriptions_cycles.discount_id'
        );
    }
}
