<?php

namespace App\Console;

use App\Models\Template;
use App\Console\Commands\SendEmails;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $records = Template::where('status', 'scheduled')->get();
        if ($records->isNotEmpty()) {
            foreach ($records as $record) {
                $subscribersPerRequest = $record->subscriber_per_request;
                $requestInterval = $record->request_interval;
                $template_id = $record->id;

                $schedule
                    ->command(SendEmails::class, [
                        $template_id,
                        $subscribersPerRequest,
                    ])
                    ->cron("*/{$requestInterval} * * * *");
            }
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
