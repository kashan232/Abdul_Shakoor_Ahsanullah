<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Daily Database Backup at 11:59 PM
        $schedule->call(function () {
            \App\Http\Controllers\BackupController::sendEmailBackup();
        })->dailyAt('23:59');
    }
    protected $commands = [
        \App\Console\Commands\MakeHelper::class,
    ];
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
