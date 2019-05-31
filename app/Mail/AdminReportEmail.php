<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;

class AdminReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $file;

    public $deliveryTiming;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, $deliveryTiming = '')
    {
        $this->file = $file;
        $this->deliveryTiming = $deliveryTiming;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
        \Log::info('admin report email file:'.$this->file);

        return $this->from(env('MAIL_EMAIL'))
        ->subject('Last Cycle Report')
        ->markdown('emails.admin-last-cycle-report')
        ->with(array('deliveryTiming' => $this->deliveryTiming))
        ->attach($this->file);
    }
}
