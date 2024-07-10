<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Jaksa;
use App\Models\Peminjaman;
use App\Models\Reminder;
use App\Models\Saksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login

        if ($user->is_admin) {
            // Menghitung agenda yang belum terkirim untuk admin
            $agenda_belum_terkirim = Reminder::where('is_sent', false)->count();

            // Menghitung agenda yang terkirim untuk admin
            $agenda_terkirim = Reminder::where('is_sent', true)->count();

            // Menghitung total Jaksa untuk admin
            $total_jaksa = Jaksa::count();

            // Menghitung total Saksi untuk admin
            $total_saksi = Saksi::count();
        } else {
            // Menghitung agenda yang belum terkirim untuk pengguna biasa
            $agenda_belum_terkirim = Reminder::where('user_id', $user->id)->where('is_sent', false)->count();

            // Menghitung agenda yang terkirim untuk pengguna biasa
            $agenda_terkirim = Reminder::where('user_id', $user->id)->where('is_sent', true)->count();

            // Menghitung total Jaksa untuk pengguna biasa
            $total_jaksa = Jaksa::where('user_id', $user->id)->count();

            // Menghitung total Saksi untuk pengguna biasa
            $total_saksi = Saksi::where('user_id', $user->id)->count();
        }

        return view('dashboard.dashboard.index', compact('agenda_terkirim', 'agenda_belum_terkirim', 'total_jaksa', 'total_saksi'));
    }

    public function agendaTerkirimSesuaiJadwal()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login

        // Membuat query builder untuk agenda terkirim sesuai jadwal
        $query = Reminder::select('tanggal_waktu', DB::raw('count(id) as jumlah'))
            ->where('is_sent', true)
            ->groupBy('tanggal_waktu')
            ->orderBy('tanggal_waktu');

        // Jika bukan admin, tambahkan kondisi user_id
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        // Mendapatkan hasil query
        $agendaTerkirimSesuaiJadwal = $query->get();

        return response()->json($agendaTerkirimSesuaiJadwal);
    }

    public function agendaBelumTerkirimSesuaiJadwal()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login

        // Membuat query builder untuk agenda belum terkirim sesuai jadwal
        $query = Reminder::select('tanggal_waktu', DB::raw('count(id) as jumlah'))
            ->where('is_sent', false)
            ->groupBy('tanggal_waktu')
            ->orderBy('tanggal_waktu');

        // Jika bukan admin, tambahkan kondisi user_id
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        // Mendapatkan hasil query dan mengubah format tanggal_waktu
        $agendaBelumTerkirimSesuaiJadwal = $query->get()->map(function ($item) {
            $item->tanggal_waktu = \Carbon\Carbon::parse($item->tanggal_waktu)->toDateTimeString();
            return $item;
        });

        return response()->json($agendaBelumTerkirimSesuaiJadwal);
    }


    
}
