<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logData = file_get_contents(storage_path('logs/activity.log')); // Baca isi file activity.log
        $lines = explode("\n", $logData);

        $userLogs = [];

        foreach ($lines as $line) {
            // Ekstrak pesan dan data JSON dari log
            preg_match('/local\.INFO: (.*?) \{/', $line, $messageMatch);
            preg_match('/\{.*?\}/', $line, $dataMatch);

            if (isset($messageMatch[1]) && isset($dataMatch[0])) {
                $message = $messageMatch[1];
                $jsonData = $dataMatch[0];
                
                // Decode JSON menjadi array asosiatif
                $userData = json_decode($jsonData, true);
                
                // Tambahkan pesan ke array data
                $userData['message'] = $message;

                // Ekstrak waktu dari baris log
                preg_match('/\[(.*?)\]/', $line, $timestampMatch);
                if (isset($timestampMatch[1])) {
                    $userData['created_at'] = $timestampMatch[1];
                }
                
                // Simpan data pengguna ke dalam array
                $userLogs[] = $userData;
            }
        }

        $userLogs = array_reverse($userLogs); // Reverse the order of the array

        return view('dashboard.logs.index', compact('userLogs'));
    }
}
