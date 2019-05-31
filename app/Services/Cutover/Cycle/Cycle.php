<?php

namespace App\Services\Cutover\Cycle;

use App\Services\Cutover\Data\Cycle as ModelCycle;


Class Cycle
{   
    public function __construct()
    {
        $this->cycle = new ModelCycle;
    }

    public function activate(int $batch)
    {   
        $this->cycle->activate($batch);
    }

    public function deactivate(int $batch)
    {   
        $this->cycle->deactivate($batch);
    }

    public function getNextBatch(int $batch)
    {   
        return $this->cycle->getNextBatch($batch);
    }

    public function getActiveTimingId(int $deliveryTimingId)
    {   
        return $this->cycle->getActiveTimingId($deliveryTimingId);
    }
}
