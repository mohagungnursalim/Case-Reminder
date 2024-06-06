<?php

namespace App\Jobs;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SendReminderMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function handle()
    {
        Log::info('SendReminderMessage job started for reminder ID: ' . $this->reminder->id);

        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.whatsapp_from');
        $client = new Client($sid, $token);

        $message = "Reminder Sidang Kejaksaan:\nNama Jaksa: {$this->reminder->prosecutor_name}\nNama Kasus: {$this->reminder->case_name}\nPesan: {$this->reminder->message}";

        Log::info('Sending message from: ' . $from . ' to: whatsapp:' . $this->reminder->phone_number . ' with message: ' . $message);

        try {
            $client->messages->create(
                'whatsapp:' . $this->reminder->phone_number,
                [
                    'from' => $from,
                    'body' => $message,
                ]
            );

            Log::info('Reminder message sent successfully for reminder ID: ' . $this->reminder->id);

            $this->reminder->update(['is_sent' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to send reminder message for reminder ID: ' . $this->reminder->id . '. Error: ' . $e->getMessage());
        }
    }
}
