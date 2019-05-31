<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\Customers\Account\InfusionsoftCustomer;

use App\Services\Log;

class InfusionCustomerAddTag implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    
    private $tag;
    private $contact;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tag, array $contact)
    {
        $this->tag = $tag;
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {    
        $this->log = new Log('billing','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
        $this->log->info("Running queu to savedTagToContact.");

        $this->infusionsoft = new InfusionsoftCustomer(0, 'inline');
        $this->infusionsoft->savedTagToContact($this->tag, $this->contact);
    }
}
