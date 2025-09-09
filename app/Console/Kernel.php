<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   
        // Run backup daily at 6:00 PM
        $schedule->command('backup:run')
            ->dailyAt('18:00')
            ->emailOutputOnFailure('iversoncraigguno.bfcgroup@gmail.com');

        // Clean up old backups daily at 2:00 AM (after the backup)
        $schedule->command('backup:clean')
            ->dailyAt('02:00')  // Daily cleanup
            ->emailOutputOnFailure('iversoncraigguno.bfcgroup@gmail.com');

        // Monitor backup health daily at 8:00 AM
        // $schedule->command('backup:monitor')
        //     ->dailyAt('08:00')
        //     ->emailOutputOnFailure('iversoncraigguno.bfcgroup@gmail.com');
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
