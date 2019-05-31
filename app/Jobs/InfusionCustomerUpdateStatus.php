<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Log;

use App\Services\Customers\Account\InfusionsoftCustomer;

class InfusionCustomerUpdateStatus implements ShouldQueue
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
        $this->log = new Log('billing','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
        $this->log->info("Running queu to update User # ".$this->userId." with status '".$this->status."'");
        
        $this->infusionsoft = new InfusionsoftCustomer($this->userId, 'inline');
        $this->infusionsoft->updateStatus($this->status);
    }
}
