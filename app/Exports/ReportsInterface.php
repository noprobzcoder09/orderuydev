<?php

namespace App\Exports;

use App\Services\Reports\Criteria\WithContactId;
use App\Services\Reports\Criteria\UptoPresent;
use App\Services\Reports\Criteria\CurrentCycle;

Interface ReportsInterface
{	 
    public function data();
   
    public function byLastCycleInstance();

    public function byCurrentCycleInstance();    

    public function base();
}
