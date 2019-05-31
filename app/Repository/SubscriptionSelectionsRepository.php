<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\SubscriptionsSelections;
use App\Rules\Custom;
use DB;
use App\Traits\Auditable;

Class SubscriptionSelectionsRepository
{	
    use Auditable;
    
    public $successSavedMessage = 'Successfully created new Cycle Plan.';

    public $successUpdatedMessage = 'Successfully updated Cycle Plan.';

    public $successDeletedMessage = "Successfully deleted Cycle Plan.";

    public $errorDeleteMessage = "Sorry could not delete Cycle Plan.";

    const rules = [
        'store' => [
            'cycle_id'              => 'required',
            'meal_plans_id'         => 'required',
            'default_selections'    => 'required',
            'delivery_zone_id'      => 'required'
        ],

        'edit' => [
            'cycle_id'              => 'required',
            'meal_plans_id'         => 'required',
            'default_selections'    => 'required'
        ],
    ];

    const primary_key = 'id';

    const subscription_id = 'subscription_id';

    const user_id = 'user_id';

    const cycle_id = 'cycle_id';

    const menu_selections = 'menu_selections';

    const cycle_subscription_status = 'cycle_subscription_status';
    const delivery_zone_id = 'delivery_zone_id';

    const status = 'status';
    const cancelled = 'cancelled';
    const active = 'active';
    const inactive = 'inactive';
    const paid = 'paid';
    const unpaid = 'unpaid';
    const billing_issue = 'billing issue';
    const paused = 'paused';
    const ins_invoice_id = 'ins_invoice_id';
    const discount_id = 'discount_id';
    const failed = 'failed';
    const pending = 'pending';
    const refunded = 'refunded';
    
    public $id;

    public function __construct() 
    {
        $this->model = new SubscriptionsSelections;
    }

    public function store(array $data): array
    {   
        $model = $this->model->create([
            self::user_id  => $data['user_id'],
            self::subscription_id => $data['subscription_id'],
            self::cycle_id => $data['cycle_id'],
            self::delivery_zone_id => $data['delivery_zone_id'],
            self::menu_selections => $data['menu_selections'],
            self::cycle_subscription_status => $data['cycle_subscription_status'],
            self::ins_invoice_id => $data['ins_invoice_id'] ?? '',
            self::discount_id => $data['discount_id'] ?? ''
        ]);

        $this->setId($model->id);

        return (array)$this->model = $model;
    }

    public function updateStatusByTimingId(int $id, int $status): array
    {   
        return
        (array)$this->model->where(self::primary_key, $id)
        ->update([
            self::status  => $status,
        ]);
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::user_id  => $data['user_id'],
            self::cycle_id => $data['cycle_id'],
            self::menu_selections => $data['menu_selections'],
            self::cycle_subscription_status => $data['cycle_subscription_status']
        ]);
    }

    public function updateByCycleSubscription(int $cycleId, int $subscriptionId, array $data)
    {   
        return
        $this->model->where([
            self::cycle_id => $cycleId,
            self::subscription_id => $subscriptionId,
        ])
        ->update([
            self::user_id  => $data['user_id'],
            self::cycle_id => $data['cycle_id'],
            self::menu_selections => $data['menu_selections'],
            self::cycle_subscription_status => $data['cycle_subscription_status']
        ]);
    }

    public function getIdByCycleSubscription(int $cycleId, int $subscriptionId)
    {   
        $d = $this->model->where([
            self::cycle_id => $cycleId,
            self::subscription_id => $subscriptionId,
        ])
        ->first();

        return $d->id ?? 0;
    }

    
    public function updateSelections(int $userId, int $id, string $selections): array
    {   
        return
        (array)$this->model->where([
            'id' => $id,
            'user_id' => $userId
        ])
        ->update([
            self::menu_selections => $selections
        ]);
    }

    public function updateInvoice(int $id, int $invoiceId)
    {   
        return $this->model->where('id', $id)
        ->update([
            self::ins_invoice_id => $invoiceId
        ]);
    }

    public function updateToPaid(int $id)
    {   
        return $this->model->where(self::primary_key, $id)
        ->update([
            self::cycle_subscription_status => 'paid'
        ]);
    }

    public function delete(int $id): array
    {
        return [$this->model->where(self::primary_key, $id)->delete()];
    }

    public function search(): array
    {
        return [];
    }

    public function verify(string $value): string
    {
        return $this->model->where(self::name,$value)->count() > 0;
    }

    public function get(int $subSId)
    {
        return $this->model->where(self::subscription_id,$subSId)->get();
    }

    public function getIds(int $subSId)
    {
        return $this->model->select('id')->where(self::subscription_id,$subSId)->get();
    }

    public function getLatest(int $subSId)
    {
        return $this->model->where(self::subscription_id,$subSId)->orderBy('id','desc')->first();
    }

    public function getById(int $id)
    {
        return $this->model->where(self::primary_key,$id)->first();
    }

    public function storeRules(): array
    {
        $rules = self::rules['store'];

        return $rules;
    }

    public function updateRules(): array
    {
        $rules = self::rules['edit'];

        return $rules;
    }

    public function inactive(int $id): array
    {
        $this->model->where(self::primary_key, $id)        
                ->update(['status' => 0]);
    }

    public function inactiveByCycle(int $cycleId)
    {
        $this->model->where(self::cycle_id, $cycleId)        
                ->update(['status' => 0]);
    }

    public function inactiveByUser(int $userId)
    {
        $this->model->where(self::user_id, $userId)        
                ->update(['status' => 0]);
    }

    public function inactiveByUserAndSubscribeId( int $subscriptionId, int $userId)
    {
        $this->model
                ->where(self::user_id, $userId)        
                ->where(self::subscription_id, $subscriptionId)
                ->update(['status' => 0]);
    }

    public function inactiveByUserAndArrayId(int $userId, array $id)
    {
        $this->model
                ->where(self::user_id, $userId)        
                ->whereIn(self::primary_key, $id)
                ->update(['status' => 0]);
    }
    

    public function getAll()
    {
        return $this->model->get();
    }

    public function iHaveIt(int $cycleId, int $subscriptionId)
    {
        return $this->model->where([self::cycle_id => $cycleId, self::subscription_id => $subscriptionId])
                    ->count() > 0;
    }  

    public function getActiveByPlanId(int $id)
    {
        return $this->model->where(self::meal_plans_id,$id)->first();
    }

    public function getCycleIdById(int $id)
    {
        $d = $this->model->where(self::primary_key,$id)->first();
        return $d->cycle_id ?? 0;
    }

    public function getSubscriptionIdById(int $id)
    {
        $d = $this->model->where(self::primary_key,$id)->first();
        return $d->subscription_id ?? 0;
    }

    public function getActiveByCycleId(int $id)
    {
        return $this->model
            ->where([self::status => 1])
            ->orderBy(self::primary_key,'desc')
            ->get();
    }

    public function getCurrent(int $id)
    {
        return $this->model
            ->where([self::subscription_id => $id])
            ->orderBy(self::primary_key,'desc')
            ->first();
    }

    public function getSelectionCyclesBySubscription(int $id)
    {
        return $this->model
            ->where([self::subscription_id => $id])
            ->orderBy(self::primary_key,'desc')
            ->get();
    }

    public function getCurrentMealSelection(int $userID, int $cycleID)
    {
        return $this->model
            ->where([self::cycle_id => $cycleID])
            ->where([self::user_id => $userID])
            ->orderBy(self::primary_key,'desc')
            ->get();
    }
    

    public function getLatestSelectionByCycleId(int $id, int $cycleId)
    {
        $d = $this->model->where([self::subscription_id => $id, self::cycle_id => $cycleId])->orderBy('id','desc')->first();
        return isset($d->menu_selections) ? json_decode($d->menu_selections) : [];
    }

    public function getLatestSelection(int $id)
    {
        $d = $this->model->where([self::subscription_id => $id])->orderBy('id','desc')->first();
        return isset($d->menu_selections) ? json_decode($d->menu_selections) : [];
    }

    public function isAdded(int $userId, int $cycleId, int $subscriptionId)
    {
        return $this->model->where([
            self::user_id => $userId,
            self::cycle_id => $cycleId,
            self::subscription_id => $subscriptionId
        ])
        ->count() > 0;
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


    public function getPreviousSelections(int $userId)
    {   
        $cycleRepo = new \App\Repository\CycleRepository;
        // Get Current Active Cycle ID
        $activeCycleId = $cycleRepo->getActiveId();

        // Get speficic selections
        $currCycleSelection = $this->model->where([self::cycle_id => $activeCycleId,self::user_id => $userId])->first();
        
        // If no selections, then retrieve the latest selections
        if (!isset($currCycleSelection->id)) 
        {
            $selections = $this->model->getLatest($userId);
        } 
        else 
        {
            // Otherwise, get previous selections (not the current)
            $selections = $this->model->where(self::cycle_id,'<',$activeCycleId)
                    ->where(self::user_id,$userId)
                    ->orderBy(self::primary_key,'desc')
                        ->first();

            // If no previous selections, then retrieve the latest selections
            if (!isset($selections->id)) {
                $selections = $this->model->getLatest($userId);
            }
        }

        /*
        // Check if the cycle cutover is due then proceed to the next cycle
        $cycle = $cycleRepo->get($selections->cycle_id);

        $now = new \DateTime(date('Y-m-d'));
        $cutover = new \DateTime($cycle->cutover_date);
        $delivery = new \DateTime($cycle->delivery_date);
        
        if ($cutover <= $now) {
            $cycle = $cycleRepo->getNextCycle($selections->cycle_id);
        }
        */
        
        return $selections;
                
    }  

    private function setId(int $id)
    {
        $this->id = $id;
    }

    public function saveStopTillDate(int $userId, int $subscriptionCycleId)
    {
        return $this->model->where([
            self::user_id => $userId,
            self::primary_key => $subscriptionCycleId
        ])
        ->whereIn('cycle_subscription_status', [
            self::pending
        ])
        ->update([
            'cycle_subscription_status' => self::paused
        ]);
    }

     public function cancelPausedDate(int $userId, int $subscriptionCycleId)
    {   
        $model = $this->model->find($subscriptionCycleId);
        // $status = 'pending';
        // if(!empty($model->ins_invoice_id)) {
        //     $invoice = new \App\Repository\SubscriptionInvoiceRepository;
        //     $invoice = $invoice->getByInvoiceId($model->ins_invoice_id);
        //     if (!empty($invoice->status)) {
        //         $status =  $invoice->status;
        //     }

        // }
        return $this->model->where([
            self::user_id => $userId,
            self::primary_key => $subscriptionCycleId
        ])
        ->whereIn('cycle_subscription_status', [
            self::paused
        ])
        ->update([
            'cycle_subscription_status' => self::pending
        ]);
    }

     public function saveStopAllTillDate(int $userId)
    {
        return $this->model->where([
            self::user_id => $userId,
        ])
        ->whereRaw(
            'cycle_id in (select id from cycles where status=1)'
        )
        ->whereIn('cycle_subscription_status', [self::pending])
        ->update([
            'cycle_subscription_status' => self::paused
        ]);
    }

    public function cancellPlan(int $userId, int $subscriptionCycleId)
    {
        return $this->model->where([
            self::user_id => $userId,
            self::primary_key => $subscriptionCycleId
        ])
        ->whereNotIn('cycle_subscription_status', [self::paid])
        ->update([
            'cycle_subscription_status' => self::cancelled,
            'cancelled_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function cancellPlanCurrentWeek(int $userId, int $subscriptionId)
    {
        return $this->model->where([
            self::user_id => $userId,
            self::subscription_id => $subscriptionId
        ])
        ->whereRaw(
            'cycle_id in (select id from cycles where status=1)'
        )
        ->whereNotIn('cycle_subscription_status', [self::paid, self::refunded])
        ->update([
            'cycle_subscription_status' => self::cancelled,
            'cancelled_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function cancellAllPlans(int $userId)
    {
        return $this->model->where([
            self::user_id => $userId
        ])
        // ->whereIn('cycle_subscription_status', [self::active, self::paused, self::paid, self::unpaid])
        ->whereRaw('cycle_id in (select id from cycles where status = 1)')
        ->whereNotIn('cycle_subscription_status', [self::paid, self::refunded])
        ->update([
            'cycle_subscription_status' => self::cancelled,
            'cancelled_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function updatestatus(int $userId, int $subscriptionCycleId, string $status)
    {   
        $status = strtolower($status);
        if (!in_array($status, ['cancelled','paused','refunded','paid','pending'])) {
            throw new \Exception("Status could not be found.", 1);
        }

        $model = $this->model->where([
            self::user_id => $userId,
            self::primary_key => $subscriptionCycleId,
        ]);

        if ($status == 'cancelled') {
            return $this->cancellPlan($userId, $subscriptionCycleId);
        }

        elseif ($status == 'paused') {
            return $this->saveStopTillDate($userId, $subscriptionCycleId);
        }

        else {
            return $model->update([
                self::cycle_subscription_status => $status
            ]);
        }
    }

    public function getCycle(int $userId, int $subscriptionId, int $subscriptionCycleId)
    {       
        return $this->model->where([
            self::user_id => $userId
        ])
        ->where('subscriptions_cycles.id',$subscriptionCycleId)
        ->where(self::subscription_id,'=',$subscriptionId);
    }

    public function getSubscriptionCycle(int $userId, int $subscriptionId, int $subscriptionCycleId)
    {       
        return $this->model->where([
            self::user_id => $userId
        ])
        ->whereNotIn(self::cycle_subscription_status, [self::cancelled, self::inactive, self::billing_issue])
        ->where(self::cycle_id,$subscriptionCycleId)
        ->where(self::subscription_id,'=',$subscriptionId);
    }

    
    public function updateCurrentSubscriptionWeekCycleId(int $userId, int  $cycleId, int $deliveryZoneId)
    {
        $cycleRepo = new \App\Repository\CycleRepository;

        $activeCycle = array();
        foreach($cycleRepo->getActive() as $row) {
            array_push($activeCycle, $row->id);
        }
        
        $this->model
            ->where('user_id', $userId)
            ->whereIn('cycle_subscription_status', array('active','paused','unpaid','paid', 'pending'))
            ->whereIn('cycle_id', $activeCycle)
            ->update([
                'cycle_id' => $cycleId,
                'delivery_zone_id' => $deliveryZoneId
            ]);
    }  


    public function updateSelectionsInArrayById(int $id, string $selections)
    {   
        return
        $this->model->where([
            'id' => $id,
        ])
        ->update([
            self::menu_selections => $selections
        ]);
    }
    

}
