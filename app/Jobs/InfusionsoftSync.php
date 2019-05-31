<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\Sync\Sync\DeliveryZone\DeliveryZoneSync;
use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Sync\Sync;

use App\Services\Log;

class InfusionsoftSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {    
        $this->log = new Log('sync','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
        $this->log->info("Running queu to sync.");

        $api = (new InfusionsoftFactory('oauth2'))->service();
        $deliveryZone = new DeliveryZoneSync($api);
        $sync = new Sync($deliveryZone);
        $sync->run($api);
    }
}
