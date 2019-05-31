<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Log;

use App\Services\Customers\Account\InfusionsoftCustomer;

class InfusionCustomerActiveMenuDelivery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;
    
    private $userId;
    private $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $userId, $status = '')
    {
        $this->userId = $userId;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $this->log = new Log('queue','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
        $this->log->info("Running queu to activeMenuDelivery User # ".$this->userId);
        
        $this->infusionsoft = new InfusionsoftCustomer($this->userId, 'inline');
        $this->infusionsoft->activeMenuDelivery();
    }
}
