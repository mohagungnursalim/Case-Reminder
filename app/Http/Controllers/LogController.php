<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logData = file_get_contents(storage_path('logs/login.log')); // Baca isi file log
        $lines = explode("\n", $logData);

        $userLogs = [];

        foreach ($lines as $line) {
            // Ekstrak bagian JSON menggunakan ekspresi reguler
            preg_match('/\{.*?\}/', $line, $match);
            if (isset($match[0])) {
                $jsonData = $match[0];
            
                // Decode JSON menjadi array asosiatif
                $userData = json_decode($jsonData, true);
            
                // Ekstrak waktu login dari baris log
                preg_match('/\[(.*?)\]/', $line, $waktuLoginMatch);
                if (isset($waktuLoginMatch[1])) {
                    $userData['waktu_login'] = $waktuLoginMatch[1];
                }
            
                // Simpan data pengguna ke dalam array
                $userLogs[] = $userData;
            }
        }

        $userLogs = array_reverse($userLogs); // Reverse the order of the array

        return view('dashboard.logs.index', compact('userLogs'));
    }
}
