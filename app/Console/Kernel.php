<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $logPath1 = storage_path('logs/LogExampleCron');
        // Ensure the directory exists
        if (!file_exists($logPath1)) {
            mkdir($logPath1, 0777, true);
        }
        
        $now = Carbon::now()->format('YmdHis');

        $schedule->command('ExampleCron')
            ->timezone('Asia/Jakarta')
            ->dailyAt('23:59')
            // ->everyMinute()
            ->sendOutputTo("storage/logs/LogExampleCron/LogExampleCron_" . $now . ".txt");
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
