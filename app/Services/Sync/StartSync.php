<?php

namespace App\Services\Sync;

use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Sync\Data;

Class StartSync
{       
    public function __construct()
    {
        $this->data = new Data;
    }

    public function handle()
    {
        \App\Jobs\InfusionsoftCustomerSync::dispatch($row->id);
        ->delay(now()->addMinutes(1));
    }

}
