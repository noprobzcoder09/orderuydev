<?php

namespace App\Services\Reports\Criteria\Customer;

use App\Services\Reports\Request;
use App\Services\Reports\Joins;

Class WithTiming extends Joins
{      
    private $deliveryTimingId;
	public function __construct(int $deliveryTimingId)
	{
		$this->deliveryTimingId = $deliveryTimingId;
	}
	
	public function apply($model)
	{	       
        $model->join('delivery_timings',
            'delivery_timings.id','=',
            'cycles.delivery_timings_id'
        );

		return $model->where('delivery_timings.id', 
            $this->deliveryTimingId
        );
	}
    
}

