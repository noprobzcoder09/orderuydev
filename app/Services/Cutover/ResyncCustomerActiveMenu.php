<?php

namespace App\Services\Cutover;
 
use App\Services\Cutover\Cycle\ResyncCustomerActiveMenu as ResyncCustomerActiveMenuHandler;
use App\Services\Cutover\Data\Cycle;
use App\Services\Log;

Class ResyncCustomerActiveMenu
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

            $sync = new ResyncCustomerActiveMenuHandler(
                $cycle->id,
                $cycle->delivery_timings_id,
                new \DateTime($cycle->delivery_date)
            );

            $sync->handle();
        }
    }

}