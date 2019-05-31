<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\Sync\SyncAbstract;

use App\Services\Log;

class InfusionsoftCustomerSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    
    private $syncData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SyncAbstract $sync)
    {
        $this->syncData = $sync;
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

        $infusionsoftCustomer = new InfusionsoftCustomer(0, 'inline');
        $infusionsoftCustomer->sync($this->syncData);
    }
}
