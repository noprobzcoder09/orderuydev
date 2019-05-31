<?php

namespace App\Services\Cutover;

use App\Services\Cutover\Cycle\RecurBilling;
use App\Services\Cutover\Cycle\Timing;
use App\Services\Cutover\Data\Cycle;
use App\Services\Log;
use DB;

Class Billing
{   
    public function __construct($date = '')
    {
        $this->date = $date;
        $this->cycle = new Cycle;
        $this->log = new Log('cutover','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

    public function handle()
    {   
        DB::beginTransaction();
        try 
        {
            $data = array();
            foreach($this->cycle->getByCurrentDate($this->date) as $cycle)
            {   
                $cycle = (object)$cycle;
                
                $previousCycle = $this->cycle->getPreviousByTimingAndCutoffDate(
                    $cycle->delivery_timings_id, 
                    new \DateTime($cycle->cutover_date)
                );
                
                $billing = new RecurBilling(
                    $cycle->id, 
                    $previousCycle->id ?? 0, 
                    $cycle->delivery_timings_id,
                    new \DateTime($this->date),
                    new \DateTime($cycle->delivery_date),
                    new \DateTime($previousCycle->delivery_date ?? null) 
                );

                $billing->handle();
            }
            
            DB::commit();
        }
        catch(\Exception $e)
        {   
            DB::rollback();
            $this->log->error('Error recurring billing: '.$this->date.':'.$e->getMessage());
            throw $e;
        }
    }

}


/*

    PRODUCT     TOTAL
    7 Days Lunch & Dinners - Vegetarian x 1     $196.00/ Week
    5 Days Lunch & Dinners - Vegetarian x 1     $140.00/ Week
    7 Days Lunch & Dinners x 1  $196.00/ Week
    SUBTOTAL    $532.00/ Week
    DISCOUNTS    
    RECUR   $25.00
    RECUR2  $12.00
    PROMO2 one-time
        (7 Days Lunch & Dinners) $10.00
    PROMO1 one-time     $10.00
    TOTAL DISCOUNTS     $151.00
    TOTAL THIS WEEK     $381.00
    TOTAL AFTER THIS WEEK   $421.00/ Week

*/