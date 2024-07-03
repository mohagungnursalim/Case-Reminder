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
        if ($this->reminder->is_sent) {
            Log::info('Reminder ID ' . $this->reminder->id . ' has already been sent. Skipping.');
            return;
        }

        Log::info('SendReminderMessage job started for reminder ID: ' . $this->reminder->id);

        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
            Log::error('Twilio configuration values are missing.');
            return;
        }

        $client = new Client($sid, $token);

        $nama_atasan = is_string($this->reminder->nama_atasan) ? json_decode($this->reminder->nama_atasan, true) : $this->reminder->nama_atasan;
        $nama_jaksa = is_string($this->reminder->nama_jaksa) ? json_decode($this->reminder->nama_jaksa, true) : $this->reminder->nama_jaksa;
        $nama_saksi = is_string($this->reminder->nama_saksi) ? json_decode($this->reminder->nama_saksi, true) : $this->reminder->nama_saksi;

        $nama_atasan_str = is_array($nama_atasan) ? implode(' , ', $nama_atasan) : $nama_atasan;
        $nama_jaksa_str = is_array($nama_jaksa) ? implode(' , ', $nama_jaksa) : $nama_jaksa;
        $nama_saksi_str = is_array($nama_saksi) ? implode(' , ', $nama_saksi) : $nama_saksi;

        Log::debug('Nama Atasan: ' . $nama_atasan_str);
        Log::debug('Nama Jaksa: ' . $nama_jaksa_str);
        Log::debug('Nama Saksi: ' . $nama_saksi_str);

        $message = "*Reminder Sidang Kejaksaan!!* 
        \n\n\n{$this->reminder->pesan}
        
        \n*Nama Atasan:* $nama_atasan_str
        \n*Nama Jaksa:* $nama_jaksa_str
        \n*Nama Saksi:* $nama_saksi_str
        \n*Nama Kasus:* {$this->reminder->nama_kasus}
        \n*Tanggal/Waktu:* {$this->reminder->tanggal_waktu->format('d-m-Y H:i A')}";

        $nomor_jaksa = is_string($this->reminder->nomor_jaksa) ? json_decode($this->reminder->nomor_jaksa, true) : $this->reminder->nomor_jaksa;
        $nomor_atasan = is_string($this->reminder->nomor_atasan) ? json_decode($this->reminder->nomor_atasan, true) : $this->reminder->nomor_atasan;

        // Menggabungkan nomor_jaksa dan nomor_atasan ke dalam satu array
        $nomor_penerima = array_merge((array)$nomor_jaksa, (array)$nomor_atasan);

        Log::debug('Nomor Penerima: ' . json_encode($nomor_penerima));

        $allMessagesSent = true;

        foreach ($nomor_penerima as $nomor) {
            try {
                $response = $client->messages->create(
                    'whatsapp:' . $nomor,
                    [
                        'from' => $from,
                        'body' => $message,
                    ]
                );

                $logData = [
                    'DATE' => $response->dateCreated->format('Y-m-d H:i:s'),
                    'DIRECTION' => $response->direction,
                    'FROM' => $response->from,
                    'TO' => $response->to,
                    'STATUS' => $response->status,
                ];

                Log::info('Reminder message sent successfully to: ' . $nomor);
                Log::info('Twilio response: ' . json_encode($logData));

            } catch (\Exception $e) {
                Log::error('Failed to send reminder message to: ' . $nomor . '. Error: ' . $e->getMessage());
                $allMessagesSent = false;
            }
        }

        if ($allMessagesSent) {
            $this->reminder->update(['is_sent' => true]);
        }
    }

}
