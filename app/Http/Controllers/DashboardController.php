<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Jaksa;
use App\Models\Peminjaman;
use App\Models\Reminder;
use App\Models\Saksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
    
        $cacheKey = $user->is_admin ? 'dashboard_admin' : 'dashboard_user_' . $user->id;
        $data = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user) {
            if ($user->is_admin) {
                // Menghitung data untuk admin
                return [
                    'agenda_belum_terkirim' => Reminder::where('is_sent', false)->count(),
                    'agenda_terkirim' => Reminder::where('is_sent', true)->count(),
                    'total_jaksa' => Jaksa::count(),
                    'total_saksi' => Saksi::count(),
                ];
            } else {
                // Menghitung data untuk pengguna biasa
                return [
                    'agenda_belum_terkirim' => Reminder::where('user_id', $user->id)->where('is_sent', false)->count(),
                    'agenda_terkirim' => Reminder::where('user_id', $user->id)->where('is_sent', true)->count(),
                    'total_jaksa' => Jaksa::where('user_id', $user->id)->count(),
                    'total_saksi' => Saksi::where('user_id', $user->id)->count(),
                ];
            }
        });
    
        return view('dashboard.dashboard.index', $data);
    }
    

    public function agendaTerkirimSesuaiJadwal()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
    
        $cacheKey = $user->is_admin ? 'agenda_terkirim_admin' : 'agenda_terkirim_user_' . $user->id;
        $agendaTerkirimSesuaiJadwal = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user) {
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
            return $query->get();
        });
    
        return response()->json($agendaTerkirimSesuaiJadwal);
    }
    
    public function agendaBelumTerkirimSesuaiJadwal()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
    
        $cacheKey = $user->is_admin ? 'agenda_belum_terkirim_admin' : 'agenda_belum_terkirim_user_' . $user->id;
        $agendaBelumTerkirimSesuaiJadwal = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user) {
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
            return $query->get()->map(function ($item) {
                $item->tanggal_waktu = \Carbon\Carbon::parse($item->tanggal_waktu)->toDateTimeString();
                return $item;
            });
        });
    
        return response()->json($agendaBelumTerkirimSesuaiJadwal);
    }


    
}
