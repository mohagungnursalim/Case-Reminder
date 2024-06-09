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
        // Periksa apakah reminder sudah dikirim
        if ($this->reminder->is_sent) {
            Log::info('Reminder ID ' . $this->reminder->id . ' sudah terkirim, tidak akan mengirim lagi.');
            return;
        }

        Log::info('SendReminderMessage job started for reminder ID: ' . $this->reminder->id);

        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $from = config('services.twilio.whatsapp_from');
        $client = new Client($sid, $token);

        // Decode JSON jika perlu
        $nama_jaksa = is_string($this->reminder->nama_jaksa) ? json_decode($this->reminder->nama_jaksa, true) : $this->reminder->nama_jaksa;
        $nama_saksi = is_string($this->reminder->nama_saksi) ? json_decode($this->reminder->nama_saksi, true) : $this->reminder->nama_saksi;

        // Konversi array menjadi string yang dipisahkan dengan koma
        $nama_jaksa_str = is_array($nama_jaksa) ? implode(', ', $nama_jaksa) : $nama_jaksa;
        $nama_saksi_str = is_array($nama_saksi) ? implode(', ', $nama_saksi) : $nama_saksi;

        Log::debug('Nama Jaksa: ' . $nama_jaksa_str);
        Log::debug('Nama Saksi: ' . $nama_saksi_str);

        $message = "Reminder Sidang Kejaksaan!! \n\n\nNama Jaksa:$nama_jaksa_str\nNama Saksi: $nama_saksi_str\nNama Kasus: {$this->reminder->nama_kasus}\n*Pesan:* {$this->reminder->pesan}";

        $nomor_jaksa = is_string($this->reminder->nomor_jaksa) ? json_decode($this->reminder->nomor_jaksa, true) : $this->reminder->nomor_jaksa;

        Log::debug('Nomor Jaksa: ' . json_encode($nomor_jaksa));

        foreach ($nomor_jaksa as $nomor) {
            try {
                $response = $client->messages->create(
                    'whatsapp:' . $nomor,
                    [
                        'from' => $from,
                        'body' => $message,
                    ]
                );

                Log::info('Reminder message sent successfully to: ' . $nomor);
                Log::info('Twilio response: ' . json_encode($response));

            } catch (\Exception $e) {
                Log::error('Failed to send reminder message to: ' . $nomor . '. Error: ' . $e->getMessage());
            }
        }

        // Update status reminder menjadi terkirim
        $this->reminder->update(['is_sent' => true]);
    }
}
