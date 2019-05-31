<?php

namespace App\Services\Reports\Criteria\Customer;

use App\Services\Reports\Request;
use App\Services\Reports\Joins;
use App\Models\Cycles;
use App\Models\Configurations;

Class ByCurrentCycle extends Joins
{      
    const PAID_STATUS = 'paid';
    const PENDING_STATUS = 'pending';
    
	public function __construct()
	{
		$this->config = new Configurations;
	}
	
	public function apply($model)
    {   
        return $model
        ->join('subscriptions_cycles',
            'subscriptions_cycles.user_id','=',
            'user_details.user_id'
        )
        ->join('cycles',
            'cycles.id','=',
            'subscriptions_cycles.cycle_id'
        )
        ->whereIn('cycle_subscription_status',[self::PAID_STATUS, self::PENDING_STATUS])
        // ->whereRaw("subscriptions_cycles.ins_invoice_id in (select ins_invoice_id from subscriptions_invoice where status='paid')")
        ->whereRaw('cycles.id in (select id from cycles where status=1)');
    }

    private function getBatch()
    {
        return $this->config->getActiveBatch();
    }
}

