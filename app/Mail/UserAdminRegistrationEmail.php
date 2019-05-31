<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Users;
use Log;

class UserAdminRegistrationEmail extends Mailable
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
    public function __construct(Users $model)
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
        Log::info('User registration email verification was sent to '.$this->model->email);
        $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($this->model);
        
        return $this->from(env('MAIL_EMAIL'))->markdown('emails.user-admin-registration')->with([
            'id'    => $this->model->id,
            'name'  => $this->model->name,
            'url'  => url('password/reset/'.$token)
        ]);
    }
}
