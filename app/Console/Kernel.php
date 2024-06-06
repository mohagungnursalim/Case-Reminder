<?php

namespace App\Console;

use App\Jobs\SendReminderMessage;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        Log::info('Scheduler is running.');

        $schedule->call(function () {
            Log::info('Looking for reminders to send.');
            
            $reminders = Reminder::where('is_sent', false)
                ->where('scheduled_time', '<=', Carbon::now())
                ->get();

            foreach ($reminders as $reminder) {
                Log::info('Dispatching SendReminderMessage job for reminder ID: ' . $reminder->id);
                SendReminderMessage::dispatch($reminder);
            }
        })->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
