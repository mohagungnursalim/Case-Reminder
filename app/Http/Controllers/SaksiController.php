<?php

namespace App\Http\Controllers;

use App\Models\Saksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class SaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $saksis = Saksi::latest()->paginate(10);$search = $request->query('search');

        if ($search) {
            $saksis = Saksi::where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('alamat', 'LIKE', "%{$search}%")
                            ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                            ->orWhere('pekerjaan', 'LIKE', "%{$search}%")
                            ->latest()->paginate(10);
        } else {
            $saksis = Saksi::latest()->paginate(10);
        }
    

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
                ->with('inputModal', true);
        }

        Saksi::create($request->all());

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
    public function update(Request $request, Saksi $saksi)
    {
        // Validasi data yang diterima dari form
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'nomor_wa' => 'required|numeric',
            'pekerjaan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editModal', $saksi->id);
        }

        // Update data saksi
        $saksi->update($request->all());
        return redirect()->route('saksi.index')->with('success', 'Data saksi telah diperbarui.');
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
}
