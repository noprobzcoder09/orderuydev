<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Subscriptions;
use Log;

class SubscriptionAccount extends Mailable
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
    public function __construct($model)
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
        $user = new \App\Models\Users;
        $user = $user->find($this->model->user_id);
        $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);

        $first_name = (new \App\Repository\CustomerRepository)->profile($this->model->user_id)->first_name;
        return $this->->from(env('MAIL_EMAIL'))->markdown('emails.customer-registration')->with([
            'id'    => $this->model->user_id,
            'name'  => $first_name,
            'url'  => url('password/reset/'.$token)
        ]);
    }
}
