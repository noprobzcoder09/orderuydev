<?php

namespace App\Services\Cutover\Cycle;

use App\Services\Cutover\Data\Cycle;
use App\Services\Cutover\Generator\WhenEmpty;
use App\Services\Cutover\Generator\Continuing;
use Configurations;

Class GenerateCycle
{   
    public function __construct()
    {
        $this->config = new Configurations;
        $this->cycle = new Cycle;
    }

    public function handle()
    {   
       if ($this->cycle->isEmpty()) {
            $generate = new WhenEmpty;
        } else {
            $generate = new Continuing;
        }
        $generate->create();
    }
}
