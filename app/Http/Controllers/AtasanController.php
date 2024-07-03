<?php

namespace App\Http\Controllers;

use App\Models\Atasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AtasanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $atasans = Atasan::where('nama', 'LIKE', "%{$search}%")->latest()->paginate(10);
        } else {
            $atasans = Atasan::latest()->paginate(10);
        }
    

        return view('dashboard.atasan.index', compact('atasans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }                                                               

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nomor_wa' => 'required|string',
            'jabatan' => 'required|string',
            'pangkat' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        $nomor_wa = $this->formatNomorWA($request->nomor_wa);

        Atasan::create([
            'nama' => $request->nama,
            'nomor_wa' => $nomor_wa,
            'jabatan' => $request->jabatan,
            'pangkat' => $request->pangkat
        ]);

        return redirect()->route('atasan.index')->with('success', 'Atasan telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Atasan $atasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Atasan $atasan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $atasan = Atasan::find($id);
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editModal', $atasan->id);
        }

        $nomor_wa = $this->formatNomorWA($request->nomor_wa);
        // Update data atasan
        $atasan->update([
            'nama' => $request->nama,
            'nomor_wa' => $nomor_wa
        ]);

        return redirect()->route('atasan.index')->with('success', 'Atasan telah diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $atasan = Atasan::find($id);

        if (!$atasan) {
            return abort(404);
        }

        $atasan->delete();

        return redirect()->route('atasan.index')->with('success', 'Atasan berhasil dihapus.');
    }

      // Fungsi untuk memformat nomor WhatsApp
      private function formatNomorWA($nomor_wa)
      {
          // Hapus tanda minus (-) dari nomor
          $nomor_wa = str_replace('-', '', $nomor_wa);
      
          // Hapus karakter non-digit lainnya dari nomor
          $nomor_wa = preg_replace('/\D/', '', $nomor_wa);
      
          // Jika nomor dimulai dengan 0, hapus 0 pertama dan tambahkan kode negara
          if (substr($nomor_wa, 0, 1) == '0') {
              $nomor_wa = '62' . substr($nomor_wa, 1);
          }
      
          // Jika nomor tidak dimulai dengan kode negara +62, tambahkan + di depan 62
          if (substr($nomor_wa, 0, 2) != '62') {
              $nomor_wa = '62' . $nomor_wa;
          }
      
          // Tambahkan + di depan nomor
          $nomor_wa = '+' . $nomor_wa;
      
          return $nomor_wa;
      }
}
