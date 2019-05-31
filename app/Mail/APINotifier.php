<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class APINotifier extends Mailable
{
    use Queueable, SerializesModels;

      /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_EMAIL'))
        ->subject('URGENT - Token Renewal')
        ->markdown('emails.reauth-api')
        ->with([
            'link'  => url('infusionsoft/oauth/authenticate'),
        ]);
    }
}
