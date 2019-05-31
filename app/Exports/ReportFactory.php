<?php

namespace App\Exports;

use App\Services\Reports\Criteria\WithContactId;
use App\Services\Reports\Criteria\UptoPresent;
use App\Services\Reports\Criteria\CurrentCycle;

Abstract Class ReportFactory
{	 
    public abstract load();
}
