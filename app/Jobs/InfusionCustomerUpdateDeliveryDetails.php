<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\Customers\Account\InfusionsoftCustomer;

use Log;

class InfusionCustomerUpdateDeliveryDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    
    private $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {    
        Log::info("Running queu to updateCustomerDeliveryDetailsInfs for user #".$this->userId.".");

        $this->infusionsoft = new InfusionsoftCustomer($this->userId,'inline');
        $this->infusionsoft->updateCustomerDeliveryDetailsInfs();
    }
}
