<?php

namespace App\Console;

use App\Jobs\SendReminderMessage;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {   
    $schedule->call(function () {
        $reminders = DB::table('reminders')
            ->where('tanggal_waktu', '>=', now()->addHours(4))
            ->where('tanggal_waktu', '<', now()->addHours(5))
            ->where('is_sent', false)
            ->get();

            $reminders = Reminder::where('is_sent', false)->get();
            foreach ($reminders as $reminder) {
                dispatch(new \App\Jobs\SendReminderMessage($reminder));
            }
    })->hourly();
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
