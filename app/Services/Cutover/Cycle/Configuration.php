<?php

namespace App\Services\Cutover\Cycle;

use Configurations;

Class Configuration
{   
    public function __construct()
    {
        $this->config = new Configurations;
    }

    public function setActivebatch(int $batch)
    {
        return $this->config->setActivebatch($batch);
    }

    public function getActiveBatch()
    {
        return $this->config->getActiveBatch();
    }
}
