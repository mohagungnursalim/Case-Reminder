<?php

namespace App\Http\Controllers;

use App\Models\Kasus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class KasusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        $loggedInUserId = $user->id;

        if ($user->email === 'mohagungnursalim@gmail.com') {
            // Jika pengguna adalah admin, tampilkan semua data Kasus
            $kasuss = Kasus::with('user')->latest();
        } elseif ($user->is_admin) {
            // Jika bukan admin, tampilkan hanya data Kasus yang terkait dengan user_id tersebut
            $kasuss = Kasus::with('user')->where('lokasi', $user->kejari_nama)->latest();
        }else {
            $kasuss = Kasus::with('user')->where('user_id', $loggedInUserId)->latest();
        }

        // Pencarian berdasarkan query 'search'
        $search = $request->query('search');    
            
        if (Auth::user()->is_admin) {
            if ($search) {
                $kasuss = $kasuss->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi', 'LIKE', "%{$search}%");
                });
            }
        }else{
            if ($search) {
                $kasuss = $kasuss->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%");
                });
            }
        }
    
        $kasuss = $kasuss->paginate(10)->withQueryString();

        return view('dashboard.kasus.index', compact('kasuss'));
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        // Buat instance Kasus baru dan tetapkan user_id serta lokasi (kejari_nama)
        $kasus = new Kasus();
        $kasus->user_id = Auth::user()->id;
        $kasus->nama = $request->input('nama');
        $kasus->lokasi = $lokasi; // Tetapkan lokasi dari kejari_nama pengguna yang sedang login
        $kasus->save();

            // Log info ketika kasus baru berhasil disimpan
            Log::channel('activity')->info('Menambahkan kasus baru!', [
                'name' => Auth::user()->name,
                'lokasi' => $kasus->lokasi,
                'timestamp' => now()->toDateTimeString()
            ]);

        return redirect()->route('kasus.index')->with('success', 'Kasus baru telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kasus $kasus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kasus $kasus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->email == 'mohagungnursalim@gmail.com') {
            $lokasi = $request->input('lokasi'); 
        } else {
            $lokasi = Auth::user()->kejari_nama;
        }

        // Validasi data yang diterima dari form
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        // Cari data Kasus yang akan diupdate berdasarkan $id
        $kasus = Kasus::findOrFail($id);

        // Pastikan pengguna memiliki izin untuk mengupdate data
        if (!Auth::user()->is_admin) {
            // Jika bukan admin, hanya izinkan pengguna mengupdate data mereka sendiri
            if ($kasus->user_id != Auth::user()->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengupdate data ini.');
            }
        }

        // Update data kasus
        $kasus->lokasi = $lokasi;
        $kasus->nama = $request->input('nama');
        $kasus->save();

         // Log info ketika kasus baru berhasil diperbarui
         Log::channel('activity')->info('Memperbarui data kasus!', [
            'name' => Auth::user()->name,
            'lokasi' => $kasus->lokasi,
            'timestamp' => now()->toDateTimeString()
        ]);

        return redirect()->route('kasus.index')->with('success', 'Kasus telah diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kasus = Kasus::find($id);

        if (!$kasus) {
            return abort(404);
        }

        $kasus->delete();

        // Log info hapus data kasus
        Log::channel('activity')->info('Menghapus kasus!', [
            'name' => Auth::user()->name,
            'lokasi' => $kasus->lokasi,
            'timestamp' => now()->toDateTimeString()
        ]);
        return redirect()->route('kasus.index')->with('success', 'Kasus berhasil dihapus.');
    }
}
