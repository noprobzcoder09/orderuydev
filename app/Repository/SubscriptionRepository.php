<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\Subscriptions;
use App\Models\SubscriptionsDiscounts;
use App\Models\Coupons;
use Auth;
use App\Rules\Custom;

use DB;

Class SubscriptionRepository
{	
    public $successSavedMessage = 'Successfully created new Cycle Plan.';

    public $successUpdatedMessage = 'Successfully updated Cycle Plan.';

    public $successDeletedMessage = "Successfully deleted Cycle Plan.";

    public $errorDeleteMessage = "Sorry could not delete Cycle Plan.";

    const rules = [
        'store' => [
            'cycle_id'              => 'required',
            'meal_plans_id'         => 'required',
            'default_selections'    => 'required'
        ],

        'edit' => [
            'cycle_id'              => 'required',
            'meal_plans_id'         => 'required',
            'default_selections'    => 'required'
        ],
    ];

    const primary_key = 'id';
    const user_id = 'user_id';
    const meal_plans_id = 'meal_plans_id';
    const status = 'status';
    const paused_till = 'paused_till';
    const price = 'price';
    const quantity = 'quantity';
    const ins_order_id = 'ins_order_id';
    const invoice_id = 'ins_invoice_id';
    const coupon_code = 'coupon_code';
    const cancelled = 'cancelled';
    const active = 'active';
    const pending = 'pending';
    const inactive = 'inactive';
    const billing_issue = 'billing issue';
    const paused = 'paused';
    const total_discount = 'total_discount';
    const subscriptions_cycle_id = 'subscriptions_cycle_id';
    public $id;

    public function __construct() 
    {
        $this->model = new Subscriptions;
        $this->discounts = new SubscriptionsDiscounts;
        $this->coupons = new Coupons;
    }

    public function store(array $data)
    {   
        $data = [
            self::user_id  => $data['user_id'],
            self::meal_plans_id => $data['meal_plans_id'],
            self::status => $data['status'],
            // self::quantity => $data['quantity'],
            self::price => $data['price']
        ];

        if (isset($data['paused_till'])) {
            $data['paused_till'] = $data['paused_till'];
        }
        
        $model = $this->model->create($data);

        $this->setId($model->id);


        return $this->model = $model;
    }

    public function storeDiscounts(int $subscriptionCycleId, float $totalDiscount, float $totalRecurDiscount, $meta_data)
    {   
        
        $status = $this->discounts->create([
            'subscriptions_cycle_id' => $subscriptionCycleId,
            'total_discount' => $totalDiscount,
            'total_recur_discount' => $totalRecurDiscount,
            'meta_data'  => $meta_data
        ]);
        
        if(!empty($status->id)) {
            $meta_data = json_decode($meta_data);
            if (!empty($meta_data)) {
                foreach($meta_data as $row) {
                    // Set number used and flag USED if reach the max uses
                    (new \App\Services\Coupons)->setNumberUsed($row->coupon_code);
                }
            }
        }
    }


    public function updateStatusById(int $id, string $status)
    {   
        return $this->model->where(self::primary_key, $id)
        ->update([
            self::status  => $status,
        ]);
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::meal_plans_id => $data['meal_plans_id'],
            self::status => $data['status'],
            self::paused_till => $data['paused_till'],
        ]);
    }

    public function updateOrderId(int $int, string $orderId): array
    {   
        return
        (array)$this->model->where('id', $subId)
        ->update([
            self::order_id => $orderId
        ]);
    }

    public function updateInvoiceId(int $int, string $invoiceId): array
    {   
        return
        (array)$this->model->where('id', $subId)
        ->update([
            self::invoice_id => $invoiceId
        ]);
    }

    public function updateCouponCode(int $subId, string $code): array
    {   
        return
        (array)$this->model->where('id', $subId)
        ->update([
            self::coupon_code   => $code
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

    public function getAll()
    {
        return $this->model->get();
    }  

    public function get(int $subsciptionId)
    {
        return $this->model->find($subsciptionId);
    } 

    public function getPrice(int $id)
    {
       $d = $this->model->find($id);
       return $d->price ?? 0;
    }   

    public function getByUserId(int $userId)
    {
        return $this->model->where([self::user_id => $userId])
                    ->whereIn(self::status,[self::active, self::paused])->get();
    } 

    public function getByUserIdOderDesc(int $userId)
    {
        return $this->model->with(['meal_plan'])->where([self::user_id => $userId])
                    ->whereIn(self::status,[self::active, self::paused])->orderBy('id', 'DESC')->first();
    }

    public function getByUserIdWhateverStatus(int $userId)
    {
        return $this->model->where([self::user_id => $userId])->get();
    }   
    
    public function getCoupons(string $code)
    {
        return $this->coupons->where('coupon_code',$code)->first();
    } 

    public function getActiveByPlanId(int $userId, int $planId)
    {
        return $this->model->where([
            self::user_id => $userId, 
            self::meal_plans_id => $planId
        ])->first();
    }  

    public function getActive()
    {
        return $this->model->where([
            self::status => self::active
        ])->get();
    }  

    public function getActiveAndPaused()
    {
        return $this->model->whereIn(self::status, [
            self::active
        ]);
    }  

    public function getPausedPlans(int $userId)
    {
        return $this->model->whereIn(self::status, [
            self::paused
        ])
        ->join('meal_plans',
            'meal_plans.id','=','subscriptions.meal_plans_id'
        )
        ->where('subscriptions.user_id',$userId);
        // ->whereRaw(
        //     'subscriptions.id in (
        //         select subscription_id from subscriptions_cycles where user_id='.$userId.'
        //         and cycle_id in (select id from cycles where status=1)
        //     )'
        // );
    } 

    public function getCancelledPlans(int $userId)
    {
        return $this->model->whereIn(self::status, [
            self::cancelled
        ])
        ->join('meal_plans',
            'meal_plans.id','=','subscriptions.meal_plans_id'
        )
        ->where('subscriptions.user_id',$userId);
        // ->whereRaw(
        //     'subscriptions.id in (
        //         select subscription_id from subscriptions_cycles where user_id='.$userId.'
        //         and cycle_id in (select id from cycles where status=1)
        //     )'
        // );
    } 

    public function getPausedCancelledPlans(int $userId)
    {
        return $this->model->whereIn(self::status, [
            self::cancelled, self::paused
        ])
        ->join('meal_plans',
            'meal_plans.id','=','subscriptions.meal_plans_id'
        )
        ->where('subscriptions.user_id',$userId)
        ->get();
    } 

    public function getPausedDate(int $userId)
    {
        return $this->model->whereIn(self::status, [
            self::paused
        ])
        ->where('subscriptions.user_id',$userId)
        ->orderBy('paused_till','desc')
        ->first()->paused_till ?? null;
    } 

    public function getMealPlanBySubscriptionId(int $subscribeId)
    {
        return $this->model->with(['meal_plan'])->where('id', $subscribeId)->first();
    } 

    public function getPlansBySubscriptionId(int $userId, int $subscriptionId)
    {
        return $this->getActiveAndPaused()
        ->join('meal_plans',
            'meal_plans.id','=','subscriptions.meal_plans_id'
        )
        ->where('subscriptions.user_id',$userId)
        ->where('subscriptions.id', $subscriptionId)
        ->get();
    }  

    public function getLatestActivePlanId(int $userId)
    {
        return $this->model->where([self::user_id => $userId, self::status => self::active])->first();
    }  

    public function isVegetarian(int $planId)
    {
        $plan = new \App\Repository\ProductPlanRepository;
        $plan->setId($planId);
        return $plan->isVegetarian();
    }    

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function iHaveTheOrder(int $orderId)
    {
        return $this->model->where(self::ins_order_id,$orderId)->limit(1)->count() > 0;
    }

    public function isHavePlan(int $userId)
    {
        return $this->model->where(self::user_id,$userId)->limit(1)->count() > 0;
    }

    public function cancellAllPlans(int $userId)
    {
        return $this->model->where([
            self::user_id => $userId
        ])
        ->whereRaw(
            'id in (
                select subscription_id from subscriptions_cycles where user_id='.$userId.'
                and cycle_id in (select id from cycles where status=1)
            )'
        )
        ->update([
            'status' => self::cancelled,
            'cancelled_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function cancellPlan(int $userId, int $subscribeId)
    {
        return $this->model->where([self::user_id => $userId,self::primary_key => $subscribeId])
        ->update([
            'status' => self::cancelled,
            'cancelled_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getCancelledPausedMealPlan(int $userId)
    {
        return $this->model->with(['meal_plan'])->where('cancelled_at', date('Y-m-d H:i:s'))->where('status', self::cancelled)->where('user_id', $userId)->get();
    }

    public function activatePlan(int $userId, int $subscribeId)
    {
        return $this->model->where([self::user_id => $userId,self::primary_key => $subscribeId])
        ->update([
            'status' => self::active,
            'cancelled_at' => null
        ]);
    }

    public function saveStopTillDate(int $userId, int $subscribeId, \DateTime $date = null)
    {
        return $this->model->where([
            self::user_id => $userId,
            self::primary_key => $subscribeId
        ])
        ->update([
            'paused_till' => $date,
            'status'      => self::paused
        ]);
    }

    public function saveStopAllTillDate(int $userId, \DateTime $date)
    {
        return $this->model->where([
            self::user_id => $userId,
        ])
        ->whereRaw(
            'id in (
                select subscription_id from subscriptions_cycles where user_id='.$userId.'
                and cycle_id in (select id from cycles where status=1)
            )'
        )
        ->whereIn('status', ['active'])
        ->update([
            'paused_till' => $date,
            'status'      => self::paused
        ]);
    }

    public function getNewlyPausedMealPlan(int $userId, \DateTime $date)
    {
        return $this->model->with(['meal_plan'])->where('status', self::paused)->where('user_id', $userId)->get();
    }

    
    public function cancelPausedDate(int $userId, int $subscribeId)
    {
        return $this->model->where([
            self::user_id => $userId,
            self::primary_key => $subscribeId
        ])
        ->update([
            'paused_till' => null,
            'status'      => self::active
        ]);
    }

    public function getMyPlans(int $userId)
    {
        return $this->model
        ->select([
            'subscriptions.id',
            'subscriptions.meal_plans_id',
            'meal_plans.plan_name',
            'meal_plans.vegetarian'
        ])
        ->join('meal_plans',
            'meal_plans.id',
            '=','subscriptions.meal_plans_id'
        )
        ->where(['subscriptions.user_id' => $userId])
        ->whereIn('subscriptions.' . self::status, ['active','paused','pending'])
        ->orderBy('subscriptions.id','asc');
                        
    }

    public function getMyActivePlanName(int $userId)
    {
        return $this->model
        ->select([
            'menu_selections',
            'meal_plans.plan_name',
            'meal_plans.no_days',
            'meal_plans.no_meals'
        ])
        ->join('meal_plans',
            'meal_plans.id',
            '=','subscriptions.meal_plans_id'
        )
        ->join('subscriptions_cycles',
            'subscriptions_cycles.subscription_id','=','subscriptions.id'
        )
        ->where(['subscriptions.user_id' => $userId])
        ->whereRaw('subscriptions_cycles.cycle_id in (select id from cycles where status=1)')
        ->whereNotIn('subscriptions.status', ['cancelled','paused'])
        ->orderBy('meal_plans.plan_name','asc')
        ->get();
                        
    }

    public function getMyLastActivePlanName(int $userId)
    {   
        $cycles = array();
        foreach(DB::table('delivery_timings')->get() as $row){
            
            $data = DB::table('cycles')
            ->where('delivery_timings_id', $row->id)
            ->where('status','-1')
            ->orderBy('id','desc')
            ->first();

            array_push($cycles, $data->id ?? 0);
        }

        return $this->model
        ->select([
            'menu_selections',
            'meal_plans.plan_name',
            'meal_plans.no_days',
            'meal_plans.no_meals'
        ])
        ->join('meal_plans',
            'meal_plans.id',
            '=','subscriptions.meal_plans_id'
        )
        ->join('subscriptions_cycles',
            'subscriptions_cycles.subscription_id','=','subscriptions.id'
        )
        ->where(['subscriptions.user_id' => $userId])
        ->whereIn('subscriptions_cycles.cycle_id', $cycles)
        ->whereIn('subscriptions_cycles.cycle_subscription_status', ['paid'])
        ->orderBy('meal_plans.plan_name','asc')
        ->get();
                        
    }

    public function getMyLastDeliveryLocation(int $userId)
    {   
        $cycles = array();
        foreach(DB::table('delivery_timings')->get() as $row){
            
            $data = DB::table('cycles')
            ->where('delivery_timings_id', $row->id)
            ->where('status','-1')
            ->orderBy('id','desc')
            ->first();

            array_push($cycles, $data->id ?? 0);
        }

        $d = DB::table('subscriptions_cycles')
        ->select([
            'delivery_zone_id'            
        ])
        ->join('subscriptions',
            'subscriptions_cycles.subscription_id','=','subscriptions.id'
        )
        ->where(['subscriptions.user_id' => $userId])
        ->whereIn('subscriptions_cycles.cycle_id', $cycles)
        ->whereNotIn('subscriptions.status', ['cancelled','billing issue'])
        ->orderBy('subscriptions_cycles.id','desc')
        ->first();

        return $d->delivery_zone_id ?? 0;
    }

    public function getMyLastActiveDeliveryWeekDate(int $userId)
    {   
        $cycles = array();
        foreach(DB::table('delivery_timings')->get() as $row){
            
            $data = DB::table('cycles')
            ->where('delivery_timings_id', $row->id)
            ->where('status','-1')
            ->orderBy('id','desc')
            ->first();

            array_push($cycles, $data->id ?? 0);
        }

        $d = DB::table('subscriptions_cycles')
        ->select([
            'cycle_id'            
        ])
        ->where(['user_id' => $userId])
        ->whereIn('cycle_id', $cycles)
        ->whereIn('cycle_subscription_status', ['paid'])
        ->orderBy('subscriptions_cycles.id','desc')
        ->first();

        $data = DB::table('cycles')->where('id', $d->cycle_id ?? 0)->first();

        return $data->delivery_date ?? '';
    }

    public function getMyPlansWithSubscriptionCycles(int $userId)
    {
        return $this->getMyPlans($userId)
        ->join('subscriptions_cycles','subscriptions_cycles.subscription_id','=','subscriptions.id');             
                        
    }


    public function getMealsPlanIdById(int $id) 
    {
        $subscription = $this->model->find($id);
        return !empty($subscription) && !empty($subscription->meal_plans_id)    ?   $subscription->meal_plans_id    :   0;
    }
    
    
    
}

