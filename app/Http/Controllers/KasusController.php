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

        if ($user->is_admin) {
            // Jika pengguna adalah admin, tampilkan semua data Kasus
            $kasuss = Kasus::latest();
        } else {
            // Jika bukan admin, tampilkan hanya data Kasus yang terkait dengan user_id tersebut
            $kasuss = Kasus::where('user_id', $user->id)->latest();
        }

        // Pencarian berdasarkan query 'search'
        $search = $request->query('search');    
            
        if (Auth::user()->is_admin) {
            if ($search) {
                $kasuss = $kasuss->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi', 'LIKE', "%{$search}%");
                });
            }
        }else{
            if ($search) {
                $kasuss = $kasuss->where(function($query) use ($search) {
                    $query->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                });
            }
        }
    
        $kasuss = $kasuss->paginate(10);

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
        $kasus->lokasi = Auth::user()->kejari_nama; // Tetapkan lokasi dari kejari_nama pengguna yang sedang login
        $kasus->save();

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

        // Update data Saksi
        $kasus->nama = $request->input('nama');
        $kasus->save();


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

        return redirect()->route('kasus.index')->with('success', 'Kasus berhasil dihapus.');
    }
}
