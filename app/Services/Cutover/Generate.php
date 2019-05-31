<?php

namespace App\Services\Cutover;

use App\Services\Cutover\Cycle\GenerateCycle;
use DB;
    
Class Generate
{   
    public function handle()
    {   
        DB::beginTransaction();
        try {
            $generate = new GenerateCycle;
            $generate->handle();  
            DB::commit(); 
        } catch (Exception $e) {
            DB::rollback();
        }
    }
}
