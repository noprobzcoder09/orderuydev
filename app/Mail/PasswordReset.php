<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

     public $model;

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
    public function __construct(\App\Models\Users $model)
    {
        $this->model = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        return $this->view('emails.password-reset')->with([
            'id'    => $this->model->user_id,
            'name'  => $this->model->name,
            'link'  => url('customers/setup-password?ref='.\App\Services\Helper::encodeToken($this->model->id))
        ]);
    }
}
