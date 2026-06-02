<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * Note: Actual schedule timing is controlled via crontab.
     * Use ->everyMinute() so it runs every time schedule:run is called.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process queue jobs (runs every minute, processes all then exits)
        $schedule->command('queue:work --stop-when-empty --tries=3 --timeout=60')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        // Sync invoice status to Inventory - timing controlled by crontab frequency
        $schedule->command('inventory:sync-invoices')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/invoice-sync-scheduled.log'));

        // Recognize due deferred revenue schedules
        $schedule->command('deferred-revenue:recognize')
            ->dailyAt('01:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/deferred-revenue-recognize.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}