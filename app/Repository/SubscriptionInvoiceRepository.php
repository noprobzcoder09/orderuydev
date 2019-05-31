<?php

namespace App\Repository;

use Session;

use App\Rules\Custom;
use App\Services\CRUDInterface;
use App\Models\SubscriptionsInvoice;
use Auth;
use DB;

Class SubscriptionInvoiceRepository
{	
    public $successSavedMessage = 'Successfully created new Cycle Plan.';
    public $successUpdatedMessage = 'Successfully updated Cycle Plan.';
    public $successDeletedMessage = "Successfully deleted Cycle Plan.";
    public $errorDeleteMessage = "Sorry could not delete Cycle Plan.";

    const rules = [
        'store' => [
            'user_id'               => 'required',
            // 'subscriptions_cycle_id' => 'required',
            'ins_invoice_id'         => 'required',
            'ins_order_id'           => 'required'
        ],

        'edit' => [
            // 'subscriptions_cycle_id' => 'required',
            'ins_invoice_id'         => 'required',
            'ins_order_id'           => 'required'
        ],
    ];

    const primary_key = 'id';
    const subscriptions_cycle_id = 'subscriptions_cycle_id';
    const ins_invoice_id = 'ins_invoice_id';
    const ins_order_id = 'ins_order_id';
    const user_id = 'user_id';
    const status = 'status';
    const price = 'price';
    
    public $id;

    public function __construct() 
    {
        $this->model = new SubscriptionsInvoice;
    }

    public function store(array $data): array
    {   
        $data = [
            self::user_id => $data['user_id'],
            // self::subscriptions_cycle_id => $data['subscriptions_cycle_id'],
            self::ins_invoice_id => $data['ins_invoice_id'],
            self::ins_order_id => $data['ins_order_id'],
            self::price => $data['price'],
            self::status => $data['status'] ?? 'unpaid'
        ];
        
        $model = $this->model->create($data);

        $this->setId($model->id);


        return (array)$this->model = $model;
    }

    public function update(array $data): array
    {   
        
        $model = $this->model->find($data['id']);
        $model->ins_invoice_id = $data['ins_invoice_id'];
        $model->ins_order_id = $data['ins_order_id'];
        $model->price = $data['price'];
        
        if (!empty($data['status'])) {
            $model->status = $data['status'];            
        }

        $model->save();

        $this->setId($data['id']);

        return (array)$model;
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

    public function isTaken(int $userId, int $invoiceId, int $orderId)
    {
        return $this->model->where([
            'user_id' => $userId, 
            'ins_invoice_id' => $invoiceId,
            'ins_order_id' => $orderId
        ])->count() > 0;
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

    public function get(int $id)
    {
        return $this->model->find($id);
    }  

    public function setId(int $id)
    {
        return $this->id = $id;
    }  

    public function getId()
    {
        return $this->id;
    }     

    public function getByOrderId(int $orderId)
    {
        return $this->model->where(self::ins_order_id,$orderId)->get();
    } 

    public function getByInvoiceId(int $invoiceId)
    {
        return $this->model->where(self::ins_invoice_id,$invoiceId)->first();
    } 

    public function getByUserId(int $userId)
    {
        return $this->model
        ->select([
            'ins_order_id',
            'subscriptions_invoice.ins_invoice_id',
            'subscriptions_invoice.created_at',
            'subscription_id',
            'delivery_date',
            'subscriptions_cycles.id as subscriptions_cycle_id',
            'subscriptions.meal_plans_id',
            'meal_plans.plan_name',
            'subscriptions_invoice.status'
        ])
        ->join('subscriptions_cycles',
            'subscriptions_cycles.ins_invoice_id','=','subscriptions_invoice.ins_invoice_id'
        )
        ->join('cycles',
            'cycles.id','=','subscriptions_cycles.cycle_id'
        )
         ->join('subscriptions',
            'subscriptions.id','=','subscriptions_cycles.subscription_id'
        )
        ->join('meal_plans',
            'meal_plans.id','=','subscriptions.meal_plans_id'
        )
        ->where('subscriptions_invoice.user_id',$userId)
        ->orderBy('subscriptions_invoice.ins_order_id','desc')
        ->get();
    } 

    public function getInvoiceOnlyByUserId(int $userId)
    {
        return $this->model
        ->select([
            'ins_order_id',
            'subscriptions_invoice.ins_invoice_id',
            'subscriptions_invoice.created_at',
            'subscriptions_invoice.status',
            'subscriptions_cycles.subscription_id',
            'delivery_date',
            DB::raw("
            (select GROUP_CONCAT(DISTINCT plan_name) from subscriptions 
            INNER JOIN meal_plans 
            ON meal_plans.id = subscriptions.meal_plans_id
            INNER JOIN subscriptions_cycles
            ON subscriptions_cycles.subscription_id=subscriptions.id
            where
            subscriptions_cycles.cycle_subscription_status = 'paid'
            and subscriptions_cycles.user_id={$userId}
            group by subscriptions.user_id) as plan_name")
        ])
        ->join('subscriptions_cycles',
            'subscriptions_cycles.ins_invoice_id','=','subscriptions_invoice.ins_invoice_id'
        )
        ->join('cycles',
            'cycles.id','=','subscriptions_cycles.cycle_id'
        )
        ->where('subscriptions_invoice.user_id',$userId)
        ->orderBy('subscriptions_invoice.ins_order_id','desc')
        ->get();
    } 


    public function getInvoiceByUserId(int $userId)
    {
        return $this->model
        ->select([
            'ins_order_id',
            'subscriptions_invoice.ins_invoice_id',
            'subscriptions_invoice.created_at',
            'subscriptions_invoice.status',
            'subscriptions_cycles.subscription_id',
            'delivery_date',
            'plan_name'
        ])
        ->join('subscriptions_cycles',
            'subscriptions_cycles.ins_invoice_id','=','subscriptions_invoice.ins_invoice_id'
        )
        ->join('subscriptions', 
                'subscriptions_cycles.subscription_id', '=', 'subscriptions.id'
        )
        ->join('meal_plans', 
                'meal_plans.id', '=', 'subscriptions.meal_plans_id'
        )
        ->join('cycles',
            'cycles.id','=','subscriptions_cycles.cycle_id'
        )
        ->where('subscriptions_invoice.user_id',$userId)
        ->orderBy('subscriptions_invoice.ins_order_id','desc')
        ->get();
    } 


     public function getBySubscriptionCycleId(int $id)
    {
        return $this->model->where(self::subscriptions_cycle_id,$id)->first();
    } 

    public function getSubcriptionCycleIdByOrderId(int $orderId)
    {
        $d = $this->model->where(self::ins_order_id,$orderId)->first();
        return $d->subscriptions_cycle_id ?? 0;
    } 

    public function getRaw(int $userId, $orderId, $invoiceID)
    {
        return $this->model->where([
            'user_id' => $userId,
            'ins_order_id' => $orderId,
            'ins_invoice_id' => $invoiceID,
        ])->first();
    }

}

