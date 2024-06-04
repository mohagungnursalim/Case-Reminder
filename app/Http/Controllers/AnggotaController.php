<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan daftar anggota
        $anggotas = $request->search 
            ? Anggota::where('nama_lengkap', 'like', '%' . $request->search . '%')->oldest()->cursorPaginate(10)->withQueryString()
            : Anggota::oldest()->cursorPaginate(10)->withQueryString();

        return view('dashboard.anggota.index', compact('anggotas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|in:X,XI,XII',
            'jurusan' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|unique:anggota,telepon',
            'email' => 'required|string|email|unique:anggota,email',
        ]);

        // Menyimpan anggota baru
        Anggota::create($request->all());

        return redirect('/dashboard/anggota')->with('success', 'Anggota berhasil diregistrasi!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|in:X,XI,XII',
            'jurusan' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|unique:anggota,telepon,' . $id,
            'email' => 'required|string|email|unique:anggota,email,' . $id,
        ]);

        // Memperbarui anggota yang ada
        $anggota = Anggota::findOrFail($id);
        $anggota->update($request->all());

        return redirect('/dashboard/anggota')->with('success', 'Anggota berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Menghapus anggota
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();

        return redirect('/dashboard/anggota')->with('success', 'Anggota berhasil dihapus!');
    }

    /**
     * Display the specified resource.
     */
    public function cetakKartu($id)
    {
        // Temukan anggota berdasarkan ID
        $anggota = Anggota::findOrFail($id);

        // Konversi tampilan kartu anggota ke dalam bentuk HTML
        $html = view('dashboard.anggota.kartu', compact('anggota'))->render();

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Buat instance Dompdf
        $dompdf = new Dompdf($options);

        // Muat HTML ke Dompdf
        $dompdf->loadHtml($html);

        // Atur ukuran dan orientasi halaman
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Tampilkan PDF dalam browser atau unduh
        return $dompdf->stream('kartu_anggota_' . $anggota->nama_lengkap . '.pdf');
    }
}
