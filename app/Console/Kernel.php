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
        // Run backup daily at 7:00 PM (after working hours)
        $schedule->command('backup:run')
            ->dailyAt('19:00')
            ->emailOutputOnFailure('iversoncraigguno.bfcgroup@gmail.com');

        // Clean up old backups daily at 5:00 AM (only when backup is healthy)
        $schedule->command('backup:clean')
            ->dailyAt('05:00')
            ->when(function () {
                // Only run cleanup if the last backup was successful
                // You can add additional conditions here if needed
                return true; // Modify this based on your backup health check
            })
            ->emailOutputOnFailure('iversoncraigguno.bfcgroup@gmail.com');

        // Monitor backup health daily (checks expiry and backup status)
        $schedule->command('backup:monitor')
            ->daily()
            ->emailOutputOnFailure('iversoncraigguno.bfcgroup@gmail.com');

        // Check allowance expiry daily
        $schedule->command('allowance:check-expiry')
            ->dailyAt('07:00')  // Run at 7 AM
            ->emailOutputOnFailure('iversoncraigguno.bfcgroup@gmail.com');
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