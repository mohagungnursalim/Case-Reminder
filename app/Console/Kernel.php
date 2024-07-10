<?php

namespace App\Console;

use App\Jobs\SendReminderMessage;
use App\Models\Reminder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    
    // protected function schedule(Schedule $schedule)
    // {
    //     $schedule->call(function () {
    //         Log::info('Scheduler running at ' . now());
    
    //         Reminder::where('is_sent', false)
    //             ->chunk(100, function ($reminders) {
    //                 foreach ($reminders as $reminder) {
    //                     dispatch(new SendReminderMessage($reminder));
    //                     Log::info('Job Berhasil dijalankan! Reminder ID: ' . $reminder->id);
    //                 }
    //             });
    
    //         // Retry all failed jobs
    //         Artisan::call('queue:retry all');
    //         Log::info('Mencoba kembali semua job yang gagal!');
    //     })->everyThirtyMinutes();
    // }
    
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info('Scheduler running at ' . now());
    
            Reminder::where('is_sent', false)
                ->where('tanggal_waktu', '<=', Carbon::now()->addDay()) // Kondisi untuk 1 hari sebelum tanggal_waktu
                ->chunk(100, function ($reminders) {
                    foreach ($reminders as $reminder) {
                        dispatch(new SendReminderMessage($reminder));
                        Log::info('Job Berhasil dijalankan! Reminder ID: ' . $reminder->id);
                    }
                });
    
            // Retry all failed jobs
            Artisan::call('queue:retry all');
            Log::info('Mencoba kembali semua job yang gagal!');
        })->everyThirtyMinutes();
    }



    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}

