<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Log;

class Cutover implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

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
        $this->log = new Log('cutover','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');

        $cycles = new \App\Services\Cutover\Cycles(date('Y-m-d H:i:s'));
        $billing = new \App\Services\Cutover\Billing(date('Y-m-d'));

        $cycles->handle();
        $billing->handle();

        $this->log->info("System cutover script was executed.");
    }
}
