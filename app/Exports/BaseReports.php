<?php

namespace App\Exports;

use App\Exports\ReportFactory;

class BaseReports
{	 

    public function currentCycle()
    {
        return $this->applyCriteria($this->base(), $this->byCurrentCycleInstance());
    }

    public function lastCycle()
    {
        return $this->applyCriteria($this->base(), $this->byLastCycleInstance());
    }

    public function previousCycle()
    {
        return $this->applyCriteria($this->base(), $this->byPreviousCycleInstance());
    }

}
