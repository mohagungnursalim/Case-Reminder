<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Atasan;
use App\Models\Jaksa;
use App\Models\Kasus;
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
        
        // Membuat cache key berdasarkan role pengguna
        $cacheKey = $user->is_admin ? 'dashboard_admin_' . $user->id : 'dashboard_user_' . $user->id;
        if ($user->email == 'mohagungnursalim@gmail.com') {
            $cacheKey = 'dashboard_super_admin';
        }
        
        $data = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($user) {
            if ($user->email == 'mohagungnursalim@gmail.com') {
                // Menghitung data untuk super admin
                return [
                    'total_kasus' => Kasus::count(),
                    'total_atasan' => Atasan::count(),
                    'total_jaksa' => Jaksa::count(),
                    'total_saksi' => Saksi::count(),
                ];
            } elseif ($user->is_admin) {
                // Menghitung data untuk admin
                return [
                    'total_kasus' => Kasus::where('lokasi', $user->kejari_nama)->count(),
                    'total_atasan' => Atasan::where('lokasi', $user->kejari_nama)->count(),
                    'total_jaksa' => Jaksa::where('lokasi', $user->kejari_nama)->count(),
                    'total_saksi' => Saksi::where('lokasi', $user->kejari_nama)->count(),
                ];
            } else {
                // Menghitung data untuk operator
                return [
                    'total_kasus' => Kasus::where('user_id', $user->id)->count(),
                    'total_atasan' => Atasan::where('user_id', $user->id)->count(),
                    'total_jaksa' => Jaksa::where('user_id', $user->id)->count(),
                    'total_saksi' => Saksi::where('user_id', $user->id)->count(),
                ];
            }
        });
        
        return view('dashboard.dashboard.index', compact('data'));
    }

    

    public function agendaTerkirimSesuaiJadwal()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        
        $cacheKey = 'agenda_terkirim_' . ($user->email == 'mohagungnursalim@gmail.com' ? 'super_admin' : ($user->is_admin ? 'admin_' . $user->id : 'user_' . $user->id));
        
        $agendaTerkirimSesuaiJadwal = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($user) {
            // Membuat query builder untuk agenda terkirim sesuai jadwal
            $query = Reminder::select('tanggal_waktu', DB::raw('count(id) as jumlah'))
                ->where('is_sent', true)
                ->groupBy('tanggal_waktu')
                ->orderBy('tanggal_waktu');

            // Jika bukan super admin, tambahkan kondisi berdasarkan peran
            if ($user->email != 'mohagungnursalim@gmail.com') {
                if ($user->is_admin) {
                    $query->where('lokasi', $user->kejari_nama);
                } else {
                    $query->where('user_id', $user->id);
                }
            }

            // Mendapatkan hasil query dan mengubah format tanggal_waktu
            return $query->get()->map(function ($item) {
                $item->tanggal_waktu = \Carbon\Carbon::parse($item->tanggal_waktu)->toDateTimeString();
                return $item;
            });
        });

        return response()->json($agendaTerkirimSesuaiJadwal);
    }

    public function agendaBelumTerkirimSesuaiJadwal()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        
        $cacheKey = 'agenda_belum_terkirim_' . ($user->email == 'mohagungnursalim@gmail.com' ? 'super_admin' : ($user->is_admin ? 'admin_' . $user->id : 'user_' . $user->id));
        
        $agendaBelumTerkirimSesuaiJadwal = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($user) {
            // Membuat query builder untuk agenda belum terkirim sesuai jadwal
            $query = Reminder::select('tanggal_waktu', DB::raw('count(id) as jumlah'))
                ->where('is_sent', false)
                ->groupBy('tanggal_waktu')
                ->orderBy('tanggal_waktu');

            // Jika bukan super admin, tambahkan kondisi berdasarkan peran
            if ($user->email != 'mohagungnursalim@gmail.com') {
                if ($user->is_admin) {
                    $query->where('lokasi', $user->kejari_nama);
                } else {
                    $query->where('user_id', $user->id);
                }
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
