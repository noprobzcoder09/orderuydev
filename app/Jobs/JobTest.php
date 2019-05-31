<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use Mail;

class JobTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Runnig job test at ".date('Y-m-d H:i:s'));

        $model = new \App\Models\UserDetails;
        $user = new \App\Models\Users;
        $user = $user->find(2);

        Mail::to($user->email)
        ->queue(new \App\Mail\UserAdminRegistrationEmail($user));
    }
}
