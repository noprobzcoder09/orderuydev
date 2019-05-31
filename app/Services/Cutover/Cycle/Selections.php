<?php

namespace App\Services\Cutover\Cycle;

use App\Services\Cutover\Data\Cycle as ModelCycle;
use App\Services\Cutover\Cycle\Selections\Get;
use App\Services\Cutover\Generate;
use App\Services\Log;

Class Selections
{   
    public function __construct($date = '')
    {   
        $this->date = $date;
        $this->cycle = new ModelCycle;
        $this->log = new Log('cutover','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

    public function handle()
    {   
        $this->set();
    }

    public function set()
    {    
        $data = array();
        
        $cycles = $this->cycle->getByCurrentDateAtCutOffTime($this->date);
        
        if ($this->cycle->isNeedtoGenerate()) {
            $generate = new Generate;
            $generate->handle();
        }
        
        foreach($cycles as $cycle)
        {   
            $cycle = (object)$cycle;
            // if ($cycle->status == 1) {
            //     continue;
            // }

            $get = new Get($cycle->id);
            list($meals, $meals_veg) = $get->handle();

            // Store default selections
            $this->cycle->update([
                'default_selections' => json_encode($meals),
                'default_selections_veg' => json_encode($meals_veg),
                'status' => 1
            ], [
                'id' => $cycle->id
            ]);
    
            $this->cycle->deactivate($cycle->delivery_timings_id, $cycle->cutover_date);
            $this->log->info("Activating new cycle #".$cycle->id. ' for '.$cycle->cutover_date);
        }
    }

}
