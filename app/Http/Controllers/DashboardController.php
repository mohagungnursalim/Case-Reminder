<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Jaksa;
use App\Models\Peminjaman;
use App\Models\Reminder;
use App\Models\Saksi;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        // Menghitung agenda yang belum terkirim
        $agenda_belum_terkirim = Reminder::where('is_sent', false)->select('id')->count();

        // Menghitung agenda yang terkirim
        $agenda_terkirim = Reminder::where('is_sent', true)->select('id')->count();

        // Menghitung total Jaksa
        $total_jaksa = Jaksa::select('id')->count();

        // Menghitung total Saksi
        $total_saksi = Saksi::select('id')->count();

        return view('dashboard.dashboard.index', compact('agenda_terkirim','agenda_belum_terkirim','total_jaksa','total_saksi'));
    }

    public function agendaTerkirimSesuaiJadwal()
    {
        $agendaTerkirimSesuaiJadwal = Reminder::select('tanggal_waktu', DB::raw('count(id) as jumlah'))
            ->where('is_sent', true)
            ->groupBy('tanggal_waktu')
            ->orderBy('tanggal_waktu')
            ->get();

        return response()->json($agendaTerkirimSesuaiJadwal);
    }

    public function agendaBelumTerkirimSesuaiJadwal()
    {
        $agendaBelumTerkirimSesuaiJadwal = Reminder::select('tanggal_waktu', DB::raw('count(id) as jumlah'))
            ->where('is_sent', false)
            ->groupBy('tanggal_waktu')
            ->orderBy('tanggal_waktu')
            ->get()->map(function ($item) {
                $item->tanggal_waktu = \Carbon\Carbon::parse($item->tanggal_waktu)->toDateTimeString();
                return $item;
            });

        return response()->json($agendaBelumTerkirimSesuaiJadwal);
    }

    
}
