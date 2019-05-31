<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\Log;
use App\Jobs\InfusionsoftAccessTokenRenewal;
use App\Services\Sync\Sync\DeliveryZone\DeliveryZoneSync;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   
        $this->log = new Log('scheduler','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
        
        $schedule->call(function () {
            $this->log->info('Schedule is running at '.date('Y-m-d H:i:s'));
        })->everyMinute();

        $schedule->job(new InfusionsoftAccessTokenRenewal)
        ->everyMinute()
        ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Cutover)
        ->everyMinute()
        ->withoutOverlapping();

        $schedule->job(new \App\Jobs\FailedBilling)
        ->daily()
        ->withoutOverlapping();

        $schedule->job(new \App\Jobs\InfusionsoftSync)
        ->hourly()
        ->withoutOverlapping();

        $schedule->command('queue:work --sansdaemon --tries=2 --timeout=14400')
        ->everyMinute()
        ->withoutOverlapping();

        // $schedule->command('queue:work --daemon')
        //     ->everyMinute()
        //     ->withoutOverlapping();

        // $schedule->command('queue:restart')->everyMinute();
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}


// /usr/local/bin/ea-php71 /home/orsufeom/app/artisan schedule:run >> /dev/null 2>&1
// /usr/local/bin/ea-php71 /home/orsufeom/app/artisan queue:work --sansdaemon --tries=5
// /usr/local/bin/ea-php71 /home/orsufeom/app/artisan queue:restart
// /usr/local/bin/ea-php71 /home/stguyfl/app/artisan schedule:run >> /dev/null 2>&1