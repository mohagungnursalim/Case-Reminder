<?php

namespace App\Http\Controllers;

use App\Models\Jaksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        $loggedInUserId = $user->id;

        if ($user->email === 'mohagungnursalim@gmail.com') {
            // Jika pengguna adalah admin, tampilkan semua data Jaksa
            $jaksas = Jaksa::oldest();
        } elseif ($user->is_admin) {
            // Jika bukan admin, tampilkan hanya data Jaksa yang terkait dengan user_id tersebut
            $jaksas = Jaksa::where('lokasi', $user->kejari_nama)->oldest();
        }else {
            $jaksas = Jaksa::where('user_id', $loggedInUserId)->oldest();
        }

        // Pencarian berdasarkan query 'search'
        $search = $request->query('search');    
            
        if (Auth::user()->email === 'mohagungnursalim@gmail.com') {
            if ($search) {
                $jaksas = $jaksas->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                    ->orWhere('pangkat', 'LIKE', "%{$search}%")
                    ->orWhere('lokasi', 'LIKE', "%{$search}%");
                });
            }
        }else{
            if ($search) {
                $jaksas = $jaksas->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                    ->orWhere('pangkat', 'LIKE', "%{$search}%");
                });
            }
        }
    
        $jaksas = $jaksas->paginate(10)->withQueryString();

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
        if (Auth::user()->email == 'mohagungnursalim@gmail.com') {
            $lokasi = $request->input('lokasi'); 
        } else {
            $lokasi = Auth::user()->kejari_nama;
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nomor_wa' => 'required|string',
            'pangkat' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        $jaksa = new Jaksa();
        $jaksa->user_id = Auth::user()->id;
        $jaksa->lokasi = $lokasi;
        $jaksa->nama = $request->input('nama');
        $jaksa->nomor_wa = $this->formatNomorWA($request->nomor_wa); // Format nomor WhatsApp yang dimasukkan pengguna
        $jaksa->pangkat = $request->input('pangkat');
        $jaksa->save();

        Log::channel('activity')->info('Menambahkan jaksa baru!', [
            'name' => Auth::user()->name,
            'lokasi' => $lokasi,
            'timestamp' => now()->toDateTimeString()
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
        if (Auth::user()->email == 'mohagungnursalim@gmail.com') {
            $lokasi = $request->input('lokasi'); 
        } else {
            $lokasi = Auth::user()->kejari_nama;
        }

        // Cari data Jaksa yang akan diupdate berdasarkan $id
        $jaksa = Jaksa::findOrFail($id);
        // Validasi data yang diterima dari form
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nomor_wa' => 'required|string',
            'pangkat' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editModal', $jaksa->id);
        }

        // Pastikan pengguna memiliki izin untuk mengupdate data
        if (!Auth::user()->is_admin) {
            // Jika bukan admin, hanya izinkan pengguna mengupdate data mereka sendiri
            if ($jaksa->user_id != Auth::user()->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengupdate data ini.');
            }
        }

        // Update data Jaksa
        $jaksa->lokasi = $lokasi;
        $jaksa->nama = $request->input('nama');
        $jaksa->nomor_wa = $this->formatNomorWA($request->nomor_wa);
        $jaksa->pangkat = $request->input('pangkat');
        $jaksa->save();

        Log::channel('activity')->info('Memperbarui data jaksa!', [
            'name' => Auth::user()->name,
            'lokasi' => $lokasi,
            'timestamp' => now()->toDateTimeString()
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

        Log::channel('activity')->info('Menghapus jaksa!', [
            'name' => Auth::user()->name,
            'lokasi' => $jaksa->lokasi,
            'timestamp' => now()->toDateTimeString()
        ]);

        return redirect()->route('jaksa.index')->with('success', 'Data jaksa telah dihapus.');
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

        return $nomor_wa;
    }


}