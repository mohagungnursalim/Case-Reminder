<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request('search')) {
            $kategories = Kategori::where('nama_kategori', 'like', '%' . request('search') . '%')
                ->oldest()
                ->cursorPaginate(10)
                ->withQueryString();
        } else {
            $kategories = Kategori::oldest()
                ->cursorPaginate(10)
                ->withQueryString();
        }

        return view('dashboard.kategori.index', compact('kategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:50',
        ]);

        Kategori::create($request->all());

        return redirect('/dashboard/kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:25',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return redirect('/dashboard/kategori')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect('/dashboard/kategori')->with('success', 'Kategori berhasil dihapus!');
    }
}
