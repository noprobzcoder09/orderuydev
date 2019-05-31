<?php

namespace App\Services\Cutover;
 
use App\Services\Cutover\Cycle\ResyncCustomer as ResyncCustomerHandler;
use App\Services\Cutover\Data\Cycle;
use App\Services\Log;

Class ResyncCustomer
{   
    public function __construct($cutoverDate = '')
    {   
        $this->cutoverDate = $cutoverDate;
        $this->cycle = new Cycle;
    }

    public function handle()
    {   
        foreach($this->cycle->getByCurrentDate($this->cutoverDate) as $cycle)
        {   
            $cycle = (object)$cycle;
            
            $previousCycle = $this->cycle->getPreviousByTimingAndCutoffDate(
                $cycle->delivery_timings_id, 
                new \DateTime($cycle->cutover_date)
            );

            $sync = new ResyncCustomerHandler(
                $cycle->id,
                $previousCycle->id ?? 0, 
                $cycle->delivery_timings_id,
                new \DateTime($cycle->delivery_date)
            );

            $sync->handle();
        }
    }

}