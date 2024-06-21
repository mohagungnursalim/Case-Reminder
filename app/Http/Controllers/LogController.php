<?php

namespace App\Http\Controllers;

use App\Providers\TwilioService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class LogController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function getAllStatus(Request $request)
    {
        try {
            // Ambil semua pesan dari Twilio
            $messages = $this->twilioService->getAllMessages();

            // Transform messages to Illuminate\Support\Collection for pagination
            $collection = collect($messages);

            // Paginate the transformed messages
            $perPage = 10; // Jumlah item per halaman
            $currentPage = LengthAwarePaginator::resolveCurrentPage() ?: 1;
            $pagedData = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

            // Mendapatkan path URL saat ini
            $path = $request->url();

            // Membuat objek LengthAwarePaginator dengan parameter path
            $messagesPaginated = new LengthAwarePaginator($pagedData, $collection->count(), $perPage, $currentPage, ['path' => $path]);

            // Format respons menjadi array dengan data pesan dan statusnya
            $data = [];
            foreach ($messagesPaginated as $message) {
                $data[] = [
                    'direction' => $message->direction,
                    'from' => $message->from,
                    'to' => $message->to,
                    'status' => $message->status,
                    'date_sent' => $message->dateSent->format('Y-m-d H:i:s'),
                ];
            }

            // Tampilkan data ke view 'sms-status'
            return view('dashboard.logs.index', ['messages' => $data, 'messagesPaginated' => $messagesPaginated]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            $this->twilioService->deleteAllMessages();
            return redirect()->route('get-all')->with('success', 'Semua pesan berhsail dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('get-all')->with('error', $e->getMessage());
        }
    }
}
