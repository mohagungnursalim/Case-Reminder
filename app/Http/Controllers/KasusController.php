<?php

namespace App\Http\Controllers;

use App\Models\Kasus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class KasusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $kasuss = Kasus::where('nama', 'LIKE', "%{$search}%")->latest()->paginate(10);
        } else {
            $kasuss = Kasus::latest()->paginate(10);
        }
    

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
            'nama' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        Kasus::create($request->all());

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
        $kasus = Kasus::find($id);
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'status' => 'nullable',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editModal', $kasus->id);
        }

        // Update data kasus
        $kasus->update($request->all());

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
