<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mendapatkan daftar buku dan kategori
        $bukus = request('search')
            ? Buku::with('kategori')->where('judul_buku', 'like', '%' . request('search') . '%')->oldest()->cursorPaginate(10)->withQueryString()
            : Buku::with('kategori')->oldest()->cursorPaginate(10)->withQueryString();
        
        $kategories = Kategori::oldest()->get();

        return view('dashboard.buku.index', compact('bukus', 'kategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|string|max:100',
            'judul_buku' => 'required|string',
            'pengarang' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|string',
            'jumlah' => 'required|numeric',
            'kategori' => 'required|exists:kategori,id',
        ]);

        // Membuat buku baru
        $data = $request->all();
        $buku = Buku::create($data);

        // Mengaitkan buku dengan kategori
        if (isset($data['kategori'])) {
            $buku->kategori()->attach($data['kategori']);
        }

        return redirect('/dashboard/buku')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_buku' => 'required|string|max:100',
            'judul_buku' => 'required|string',
            'pengarang' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|string',
            'jumlah' => 'required|numeric',
            'kategori' => 'required|exists:kategori,id',
        ]);

        // Memperbarui buku yang ada
        $data = $request->all();
        $buku = Buku::findOrFail($id);
        $buku->update($data);

        // Memperbarui kategori buku
        $buku->kategori()->detach();
        if (isset($data['kategori'])) {
            $buku->kategori()->attach($data['kategori']);
        }

        return redirect('/dashboard/buku')->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Menghapus buku
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect('/dashboard/buku')->with('success', 'Buku berhasil dihapus!');
    }
}
