<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showLogs()
    {
        $logFile = storage_path('logs/laravel.log'); // Ubah path sesuai dengan lokasi file log Anda
        $logs = [];

        if (File::exists($logFile)) {
            $fileContents = File::get($logFile);
            $logs = explode("\n", $fileContents);
        }

        return view('dashboard.logs.index', compact('logs'));
    }

}