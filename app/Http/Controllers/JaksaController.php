<?php

namespace App\Http\Controllers;

use App\Models\Jaksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JaksaController extends Controller
{
    /**
     * Tampilkan daftar semua jaksa.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jaksas = Jaksa::latest()->paginate(10);$search = $request->query('search');

        if ($search) {
            $jaksas = Jaksa::where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('alamat', 'LIKE', "%{$search}%")
                            ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                            ->orWhere('jabatan', 'LIKE', "%{$search}%")
                            ->latest()->paginate(10);
        } else {
            $jaksas = Jaksa::latest()->paginate(10);
        }
    

        return view('dashboard.jaksa.index', compact('jaksas'));
    }

    /**
     * Tampilkan detail jaksa dengan id tertentu.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Tampilkan formulir untuk membuat data jaksa baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
  
    }

    /**
     * Simpan data jaksa baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'nomor_wa' => 'required|string',
            'jabatan' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        // Format nomor WhatsApp yang dimasukkan pengguna
        $nomor_wa = $this->formatNomorWA($request->nomor_wa);

        Jaksa::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nomor_wa' => $nomor_wa,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('jaksa.index')->with('success', 'Data jaksa baru telah ditambahkan.');
    }


    /**
     * Tampilkan formulir untuk mengedit data jaksa dengan id tertentu.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Perbarui data jaksa dengan id tertentu.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'nomor_wa' => 'required|string',
            'jabatan' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        $jaksa = Jaksa::findOrFail($id);

        // Perbarui data jaksa dengan data yang baru
        $jaksa->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nomor_wa' => $request->nomor_wa,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('jaksa.index')->with('success', 'Data jaksa berhasil diperbarui.');
    }



    /**
     * Hapus data jaksa dengan id tertentu.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jaksa = Jaksa::find($id);

        if (!$jaksa) {
            return abort(404);
        }

        $jaksa->delete();

        return redirect()->route('jaksa.index')->with('success', 'Data jaksa telah dihapus.');
    }

    // Fungsi untuk memformat nomor WhatsApp
    private function formatNomorWA($nomor_wa)
    {
        // Hapus karakter non-digit dari nomor
        $nomor_wa = preg_replace('/\D/', '', $nomor_wa);

        // Jika nomor dimulai dengan 0, hapus 0 pertama dan tambahkan kode negara
        if (substr($nomor_wa, 0, 1) == '0') {
            $nomor_wa = '+62' . substr($nomor_wa, 1);
        }

        // Jika nomor tidak dimulai dengan kode negara +62, tambahkan + di depan 62
        if (substr($nomor_wa, 0, 1) != '+' && substr($nomor_wa, 0, 2) != '62' && strlen($nomor_wa) > 9) {
            $nomor_wa = '+' . $nomor_wa;
        }

        return $nomor_wa;
    }


}