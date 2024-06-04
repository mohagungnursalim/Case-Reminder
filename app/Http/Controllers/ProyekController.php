<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Perusahaan;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perusahaans = Perusahaan::latest()->get();
        $anggotas = Anggota::latest()->get();
        
        if ($request->has('search')) {
            $peminjamans = Peminjaman::with('perusahaans', 'anggota')
                ->where('kode_peminjaman', 'like', '%' . $request->search . '%')
                ->orWhereHas('anggota', function ($query) use ($request) {
                    $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('perusahaans', function ($query) use ($request) {
                    $query->where('judul_buku', 'like', '%' . $request->search . '%');
                })
                ->oldest()
                ->cursorPaginate(10)
                ->withQueryString();
        } else {
            $peminjamans = Peminjaman::with('perusahaans', 'anggota')
                ->oldest()
                ->cursorPaginate(10)
                ->withQueryString();
        }
        
        return view('dashboard.proyek.index', compact('peminjamans', 'perusahaans', 'anggotas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Form create tidak diimplementasikan dalam kode yang diberikan
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input yang diterima dari form
        $validasi = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|array', // Pastikan buku_id merupakan array
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date',
        ]);

        // Dapatkan tahun sekarang
        $tahun_sekarang = date('Y');
        
        // Dapatkan bulan sekarang
        $bulan_sekarang = date('m');
         
        // Dapatkan nomor urut terakhir dari tabel peminjaman
        $nomor_urut_terakhir = Peminjaman::max('id');
         
        // Jika belum ada data peminjaman, nomor urut dimulai dari 1
        if (!$nomor_urut_terakhir) {
            $nomor_urut_terakhir = 0;
        }
         
        // Tambahkan 1 ke nomor urut terakhir untuk mendapatkan nomor urut berikutnya
        $nomor_urut_berikutnya = $nomor_urut_terakhir + 1;
         
        // Format nomor urut dengan padding nol di depan jika diperlukan
        $nomor_urut_format = str_pad($nomor_urut_berikutnya, 2, '0', STR_PAD_LEFT);
         
        // Buat kode peminjaman dengan format yang diinginkan
        $kode_peminjaman = 'P' . $tahun_sekarang . $bulan_sekarang . $nomor_urut_format;

        // Buat dan simpan data peminjaman ke dalam database
        $peminjaman = Peminjaman::create([
            'anggota_id' => $validasi['anggota_id'],
            'kode_peminjaman' => $kode_peminjaman,
            'tanggal_peminjaman' => $validasi['tanggal_peminjaman'],
            'tanggal_pengembalian' => $validasi['tanggal_pengembalian'],
        ]);

        // Attach buku_id ke tabel peminjaman_buku
        $peminjaman->bukus()->attach($validasi['buku_id']);

        return redirect('/dashboard/proyek')->with('success', 'Peminjaman berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        // Form show tidak diimplementasikan dalam kode yang diberikan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        // Form edit tidak diimplementasikan dalam kode yang diberikan
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        // Validasi input yang diterima dari form
        $validatedData = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|array', // Pastikan buku_id merupakan array
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date',
            'tanggal_dikembalikan' => 'required|date', // Pastikan tanggal_dikembalikan disertakan dalam validasi
            'status' => 'required', // Menambahkan aturan validasi untuk status
        ]);

        // Hitung perbedaan hari antara tanggal pengembalian dan tanggal dikembalikan
        $tanggalPeminjaman = Carbon::parse($peminjaman->tanggal_peminjaman);
        $tanggalPengembalian = Carbon::parse($peminjaman->tanggal_pengembalian);
        $tanggalDikembalikan = Carbon::parse($validatedData['tanggal_dikembalikan']);

        // Inisialisasi variabel denda
        $denda = 0;

        // Periksa apakah tanggal dikembalikan berada di antara tanggal peminjaman dan tanggal pengembalian
        if ($tanggalDikembalikan->between($tanggalPeminjaman, $tanggalPengembalian)) {
            $denda = 0;
        } elseif ($tanggalPeminjaman->equalTo($tanggalDikembalikan)) {
            $denda = 0;
        } else {
            // Jika tidak, hitung perbedaan hari dan tentukan denda
            $perbedaanHari = $tanggalPengembalian->diffInDays($tanggalDikembalikan);
            // Hitung denda berdasarkan tarif denda per hari
            $tarifDendaPerHari = 1000;
            $denda = $perbedaanHari * $tarifDendaPerHari;
        }

        // Perbarui data peminjaman
        $peminjaman->update([
            'anggota_id' => $validatedData['anggota_id'],
            'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
            'tanggal_pengembalian' => $validatedData['tanggal_pengembalian'],
            'tanggal_dikembalikan' => $validatedData['tanggal_dikembalikan'],
            'denda' => $denda, // Simpan denda yang dihitung
            'status' => $validatedData['status']
        ]);

        // Sinkronkan buku_id ke tabel pivot peminjaman_buku
        $peminjaman->bukus()->sync($validatedData['buku_id']);

        return redirect('/dashboard/proyek')->with('success', 'Peminjaman berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return redirect('/dashboard/proyek')->with('success', 'Peminjaman berhasil dihapus!');
    }
}
