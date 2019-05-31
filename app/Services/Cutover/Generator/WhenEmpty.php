<?php

namespace App\Services\Cutover\Generator;

use App\Services\Cutover\Data\Cycle;
use App\Services\Cutover\Data\Timings;

Class WhenEmpty
{   
    private $batchCreated  = false;
    private $batch  = [];

    public function __construct()
    {
        $this->cycle = new Cycle;
        $this->timings = new Timings;
    }

    public function create()
    {       
        $data = [];
    
        $this->batchCreated = false;
        foreach($this->timings->getSchedule() as $row) 
        {
            $deliveryDay = strtolower($row->delivery_day);
            $cutoffDay = strtolower($row->cutoff_day);
            $createBatch = true;

            $cutoffdayDiff = $this->getDayIndex($cutoffDay);
            $deliverydayDiff = $this->getDayIndex($deliveryDay);

            $dayDiff = ($cutoffdayDiff > $deliverydayDiff) 
            ? ($cutoffdayDiff - $deliverydayDiff)+1
            : ($deliverydayDiff - $cutoffdayDiff);

            // Iterate the cycle base on the given number of cycles
            $this->generate($row->id, $cutoffDay, $dayDiff);
            $this->batchCreated = true;
        }
        
        return true;
    }

    private function getDayIndex($day)
    {   
        $days = $this->cycle->getDays();
        for($i = 1; $i <= count($days); $i++) {
            if (strtolower($days[$i]) == strtolower($day)) {
                return $i;
            }
        }
        return 0;
    }

    private function generate($timingId, $cutoffDay, $dayDiff)
    {
        $newCutoffDate = '';
        $newDeliveryDate = '';
        $data = [];

        $batchValue = 1;
        // Iterate the cycle base on the given number of cycles
        for($i = 0; $i < $this->cycle->getNoCycle(); $i++) {   
            // Determine the next week cycle date
            $newDeliveryDate = date('Y-m-d', strtotime($newDeliveryDate.' '.$cutoffDay));
            $newCutoverDate = date('Y-m-d', strtotime($newDeliveryDate));
            $newDeliveryDate = date('Y-m-d', strtotime($newDeliveryDate. '+'.$dayDiff.' day'));

            if (!$this->batchCreated) {
                $this->batch[] = $batchValue+$i;
            }

            /*$data[] = [
                'delivery_timings_id' => $timingId,
                'delivery_date' => $newDeliveryDate,
                'cutover_date'  => $newCutoverDate,
                'default_selections' => '',
                'default_selections_veg' => '',
                'status'        => 0,
                'batch'         => $this->batch[$i]
            ];*/

            // Bring it on
            $this->cycle->store([
                'delivery_timings_id' => $timingId,
                'delivery_date' => $newDeliveryDate,
                'cutover_date'  => $newCutoverDate,
                'default_selections' => '',
                'default_selections_veg' => '',
                'status'        => 0,
                'batch'         => $this->batch[$i]
            ]);
        }
    }
}
