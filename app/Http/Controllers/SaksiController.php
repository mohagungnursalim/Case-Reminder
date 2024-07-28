<?php

namespace App\Http\Controllers;

use App\Models\Saksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class SaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan pengguna yang sedang login
        $user = Auth::user(); 
        $loggedInUserId = $user->id;

        if ($user->email === 'mohagungnursalim@gmail.com') {
            // Super Admin: tampilkan semua data
            $saksis = Saksi::oldest();
        } elseif ($user->is_admin) {
            // Admin: tampilkan semua data pada lokasi Kejari tertentu
            $saksis = Saksi::where('lokasi', $user->kejari_nama)->oldest();
        } else {
            // Operator: tampilkan hanya data mereka sendiri
            $saksis = Saksi::where('user_id', $loggedInUserId)->oldest();
        }

        // Pencarian berdasarkan query 'search'
        $search = $request->query('search');    
            
        if (Auth::user()->email == 'mohagungnursalim@gmail.com') {
            if ($search) {
                $saksis = $saksis->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('alamat', 'LIKE', "%{$search}%")
                        ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                        ->orWhere('pekerjaan', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi', 'LIKE', "%{$search}%");
                        
                });
            }
        }else{
            if ($search) {
                $saksis = $saksis->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('alamat', 'LIKE', "%{$search}%")
                        ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                        ->orWhere('pekerjaan', 'LIKE', "%{$search}%");

                });
            }
        }
    

        $saksis = $saksis->paginate(10);

        return view('dashboard.saksi.index', compact('saksis'));
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

        if (Auth::user()->email == 'mohagungnursalim@gmail.com') {
            $lokasi = $request->input('lokasi'); 
        } else {
            $lokasi = Auth::user()->kejari_nama;
        }

        // Validasi data yang diterima dari form
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'nomor_wa' => 'nullable|string',
            'pekerjaan' => 'required|string',
            'lokasi' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        // Buat instance Saksi baru dan tetapkan user_id serta lokasi (kejari_nama)
        $saksi = new Saksi();
        $saksi->user_id = Auth::user()->id;
        $saksi->lokasi = $lokasi; // Tetapkan lokasi dari kejari_nama pengguna yang sedang login
        $saksi->nama = $request->input('nama');
        $saksi->alamat = $request->input('alamat');
        $saksi->nomor_wa = $this->formatNomorWA($request->nomor_wa); // Format nomor WhatsApp yang dimasukkan pengguna
        $saksi->pekerjaan = $request->input('pekerjaan');
        $saksi->save();

        return redirect()->route('saksi.index')->with('success', 'Data saksi baru telah ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Saksi $saksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Saksi $saksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cari data Saksi yang akan diupdate berdasarkan $id
        $saksi = Saksi::findOrFail($id);
        // Validasi data yang diterima dari form
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'nomor_wa' => 'nullable|string',
            'pekerjaan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editModal', $saksi->id);
        }

        // Pastikan pengguna memiliki izin untuk mengupdate data
        if (!Auth::user()->is_admin) {
            // Jika bukan admin, hanya izinkan pengguna mengupdate data mereka sendiri
            if ($saksi->user_id != Auth::user()->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengupdate data ini.');
            }
        }

        // Update data Saksi
        $saksi->nama = $request->input('nama');
        $saksi->alamat = $request->input('alamat');
        $saksi->nomor_wa = $this->formatNomorWA($request->nomor_wa); // Format nomor WhatsApp yang dimasukkan pengguna
        $saksi->pekerjaan = $request->input('pekerjaan');
        $saksi->save();

        return redirect()->route('saksi.index')->with('success', 'Data saksi telah berhasil diupdate.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $saksi = Saksi::find($id);

        if (!$saksi) {
            return abort(404);
        }

        $saksi->delete();

        return redirect()->route('saksi.index')->with('success', 'Data saksi telah dihapus.');
    }

    private function formatNomorWA($nomor_wa)
    {
        // Jika nomor kosong atau null, kembalikan nilai kosong
        if (empty($nomor_wa)) {
            return '';
        }

        // Hapus tanda minus (-) dari nomor
        $nomor_wa = str_replace('-', '', $nomor_wa);

        // Hapus karakter non-digit lainnya dari nomor
        $nomor_wa = preg_replace('/\D/', '', $nomor_wa);

        // Jika nomor dimulai dengan 0, hapus 0 pertama dan tambahkan kode negara
        if (substr($nomor_wa, 0, 1) == '0') {
            $nomor_wa = '62' . substr($nomor_wa, 1);
        }

        // Jika nomor tidak dimulai dengan kode negara 62, tambahkan 62 di depan nomor
        if (substr($nomor_wa, 0, 2) != '62') {
            $nomor_wa = '62' . $nomor_wa;
        }
        
        return $nomor_wa;
    }

}
