<?php

namespace App\Providers;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function getAllMessages()
    {
        // Ambil semua pesan dari Twilio
        return $this->client->messages->read();
    }

    public function getMessageStatus($messageSid)
    {
        try {
            $message = $this->client->messages($messageSid)->fetch();
            return [
                'sid' => $message->sid,
                'from' => $message->from,
                'to' => $message->to,
                'status' => $message->status,
                'date_sent' => $message->dateSent->format('Y-m-d H:i:s'),
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteAllMessages()
    {
        // Ambil semua pesan dari Twilio
        $messages = $this->client->messages->read();

        // Hapus setiap pesan
        foreach ($messages as $message) {
            $this->client->messages($message->sid)->delete();
        }

        return true;
    }
}
