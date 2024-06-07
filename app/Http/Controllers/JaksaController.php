<?php

namespace App\Http\Controllers;

use App\Models\Jaksa;
use Illuminate\Http\Request;
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
        $jaksas = Jaksa::latest()->paginate(10);$search = $request->query('search');

        if ($search) {
            $jaksas = Jaksa::where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('alamat', 'LIKE', "%{$search}%")
                            ->orWhere('nomor_wa', 'LIKE', "%{$search}%")
                            ->orWhere('jabatan', 'LIKE', "%{$search}%")
                            ->latest()->paginate(10);
        } else {
            $jaksas = Jaksa::latest()->paginate(10);
        }
    

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
        $validator = Validator::make($request->all(),[
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'nomor_wa' => 'required|numeric',
            'jabatan' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('inputModal', true);
        }

        Jaksa::create($request->all());

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
    public function update(Request $request, Jaksa $jaksa)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'nomor_wa' => 'required|numeric',
            'jabatan' => 'required|in:Ajun Jaksa Madya,Ajun Jaksa,Jaksa Pratama,Jaksa Muda,Jaksa Madya,Jaksa Utama Pratama,Jaksa Utama Muda,Jaksa Utama Madya',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editModal', $jaksa->id);
        }

        // Update data jaksa
        $jaksa->update($request->all());
        return redirect()->route('jaksa.index')->with('success', 'Data jaksa telah diperbarui.');
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

        return redirect()->route('jaksa.index')->with('success', 'Data jaksa telah dihapus.');
    }
}