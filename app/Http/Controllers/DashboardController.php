<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        // Menghitung total buku
        $total_buku = Buku::count();

        // Menghitung total anggota
        $total_anggota = Anggota::count();

        // Menghitung total buku yang dipinjam
        $buku_dipinjam = Peminjaman::where('status', 'Dipinjam')->count();

        // Menghitung total buku yang dikembalikan
        $buku_dikembalikan = Peminjaman::where('status', 'Dikembalikan')->count();

        // Menghitung total denda
        $total_denda = Peminjaman::sum('denda');

        // Mengambil data untuk chart
        $topBooks = DB::table('peminjaman_buku')
            ->select('buku_id', DB::raw('count(*) as total'))
            ->groupBy('buku_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $chart_data = [['Buku', 'Jumlah Peminjaman', ['role' => 'style']]];
        foreach ($topBooks as $book) {
            $judulBuku = Buku::findOrFail($book->buku_id)->judul_buku;
            $randomColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            $chart_data[] = [$judulBuku, $book->total, $randomColor];
        }
        $chart_data_json = json_encode($chart_data);

        return view('dashboard.dashboard.index', compact('total_buku', 'total_anggota', 'buku_dipinjam', 'buku_dikembalikan', 'chart_data_json', 'total_denda'));
    }
}
