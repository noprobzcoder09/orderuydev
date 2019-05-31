<?php

namespace App\Services\Cutover\Generator;

use App\Services\Cutover\Data\Cycle;
use App\Services\Cutover\Data\Timings;

Class Continuing
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
            $lastRecord = $this->cycle->getLastRecord($row->id);
            // Iterate the cycle base on the given number of cycles
            $this->generate($row->id, $lastRecord->delivery_date, $lastRecord->cutover_date);
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

    private function generate($timingId, $newDeliveryDate, $newCutoverDate)
    {
        $data = [];
        $batchValue = $this->cycle->getNewBatchByTimingId($timingId);
        // Iterate the cycle base on the given number of cycles
        for($i = 0; $i < $this->cycle->getNoCycle(); $i++) {   
            // Determine the next week cycle date
            $newDeliveryDate = date('Y-m-d', strtotime($newDeliveryDate.' +1 week'));
            $newCutoverDate = date('Y-m-d', strtotime($newCutoverDate.' +1 week'));

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
