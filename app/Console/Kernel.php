<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CheckInactiveDrivers::class,
        Commands\CheckWorkTimingNotifications::class,
        Commands\CheckInactiveUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Run the inactive driver check every minute
        $schedule->command('drivers:check-inactive')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Run the work timing notification check every minute
        $schedule->command('work-timing:check-notifications')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Run the inactive user check every minute
        $schedule->command('users:check-inactive')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();
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