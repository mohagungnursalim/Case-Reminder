<?php

namespace App\Http\Controllers;

use App\Models\Atasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AtasanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        $loggedInUserId = $user->id;

        if ($user->email === 'mohagungnursalim@gmail.com') {
            // Jika pengguna adalah admin, tampilkan semua data Atasan
            $atasans = Atasan::oldest();
        } elseif ($user->is_admin) {
            // Jika bukan admin, tampilkan hanya data Jaksa yang terkait dengan user_id tersebut
            $atasans = Atasan::where('lokasi', $user->kejari_nama)->oldest();
        } else {
            $atasans = Atasan::where('user_id', $loggedInUserId)->oldest();
        }

        // Pencarian berdasarkan query 'search'
        $search = $request->query('search');    
            
        if (Auth::user()->email === 'mohagungnursalim@gmail.com') {
            if ($search) {
                $atasans = $atasans->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                    ->orWhere('pangkat', 'LIKE', "%{$search}%")
                    ->orWhere('jabatan', 'LIKE', "%{$search}%")
                    ->orWhere('lokasi', 'LIKE', "%{$search}%");
                });
            }
        }else{
            if ($search) {
                $atasans = $atasans->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                    ->orWhere('jabatan', 'LIKE', "%{$search}%")
                    ->orWhere('pangkat', 'LIKE', "%{$search}%");
                });
            }
        }
    
        $atasans = $atasans->paginate(10)->withQueryString();
    

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
        if (Auth::user()->email == 'mohagungnursalim@gmail.com') {
            $lokasi = $request->input('lokasi'); 
        } else {
            $lokasi = Auth::user()->kejari_nama;
        }
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

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nomor_wa' => 'required|string',
            'jabatan' => 'required|string',
            'pangkat' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        $atasan = new Atasan();
        $atasan->user_id = Auth::user()->id;
        $atasan->lokasi = $lokasi;
        $atasan->nama = $request->input('nama');
        $atasan->nomor_wa = $this->formatNomorWA($request->nomor_wa); // Format nomor WhatsApp yang dimasukkan pengguna
        $atasan->jabatan = $request->input('jabatan');
        $atasan->pangkat = $request->input('pangkat');
        $atasan->save();

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
        // Cari data Atasan yang akan diupdate berdasarkan $id
        $atasan = Atasan::findOrFail($id);
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nomor_wa' => 'required|string',
            'jabatan' => 'required|string',
            'pangkat' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editModal', $atasan->id);
        }


        // Pastikan pengguna memiliki izin untuk mengupdate data
        if (!Auth::user()->is_admin) {
            // Jika bukan admin, hanya izinkan pengguna mengupdate data mereka sendiri
            if ($atasan->user_id != Auth::user()->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengupdate data ini.');
            }
        }

        // Update data Atasan
        $atasan->nama = $request->input('nama');
        $atasan->nomor_wa = $this->formatNomorWA($request->nomor_wa);
        $atasan->pangkat = $request->input('jabatan');
        $atasan->pangkat = $request->input('pangkat');
        $atasan->save();

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
      
          return $nomor_wa;
      }
}
