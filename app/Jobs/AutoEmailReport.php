<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

use App\Services\Reports\AutoEmail\AutoEmail;

class AutoEmailReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    public $type;
    public $timingId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $timingId)
    {
        $this->type = $type;
        $this->timingId = $timingId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $autoEmail = new AutoEmail($this->type, $this->timingId);
        $autoEmail->handle();
    }
}
