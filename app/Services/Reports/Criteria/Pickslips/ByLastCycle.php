<?php

namespace App\Services\Reports\Criteria\Pickslips;

use App\Services\Reports\Request;
use App\Services\Reports\Joins;
use App\Models\Cycles;
use App\Models\Configurations;
use DB;

Class ByLastCycle extends Joins
{      
    private $deliveryTimingId;

	public function __construct(int $deliveryTimingId)
	{
		$this->deliveryTimingId = $deliveryTimingId;
		$this->cycle = new Cycles;
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
        ->where('cycle_subscription_status','paid')
        // ->whereRaw("subscriptions_cycles.ins_invoice_id in (select ins_invoice_id from subscriptions_invoice where status='paid')")
        ->whereIn('cycles.id', $this->getId());        
	}


    private function getId()
    {   
        $id = [];
        $data = DB::select("SELECT id FROM cycles WHERE `status` = -1 and delivery_timings_id='".$this->deliveryTimingId."' order by id DESC limit 1");
        foreach($data as $row) {
            $id[] = $row->id;
        }
        return $id;
    }
    
}

