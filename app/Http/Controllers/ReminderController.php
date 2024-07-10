<?php

namespace App\Http\Controllers;

use App\Models\Atasan;
use App\Models\Jaksa;
use App\Models\Kasus;
use App\Models\Reminder;
use App\Models\Saksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login

        // Ambil data
        $atasans = Atasan::select('nama', 'nomor_wa')->latest()->get();
        $jaksas = Jaksa::select('nama', 'nomor_wa')->latest()->get();
        $saksis = Saksi::select('nama')->latest()->get();
        $kasuss = Kasus::select('nama')->latest()->get();

        // Membuat query builder untuk mengambil data reminder
        if ($user->is_admin) {
            // Jika pengguna adalah admin, tampilkan semua data Reminder
            $query = Reminder::latest();
        } else {
            // Jika bukan admin, tampilkan hanya data Reminder yang terkait dengan user_id tersebut
            $query = Reminder::where('user_id', $user->id)->latest();
        }

        // Pencarian berdasarkan query 'search'
        $search = $request->query('search');

        if (Auth::user()->is_admin) {
            if ($search) {
                $query = $query->where(function($query) use ($search) {
                    $query->where('nama_kasus', 'LIKE', "%{$search}%")
                        ->orWhere('pesan', 'LIKE', "%{$search}%")
                        ->orWhere('tanggal_waktu', 'LIKE', "%{$search}%")
                        ->orWhere('lokasi','LIKE', "%{$search}%");
                });
            }
        } else {
            if ($search) {
                $query = $query->where(function($query) use ($search) {
                    $query->where('nama_kasus', 'LIKE', "%{$search}%")
                        ->orWhere('pesan', 'LIKE', "%{$search}%")
                        ->orWhere('tanggal_waktu', 'LIKE', "%{$search}%");
                });
            }
        }

        // Ambil data reminder berdasarkan query yang telah dibuat lalu paginasi perbaris (10)
        $reminders = $query->paginate(10);

        return view('dashboard.agenda.index', compact('reminders', 'jaksas', 'saksis', 'kasuss', 'atasans'));
    }


    /**
     * Show the form for creating a new resource.
     */

    // halaman form
    public function create()
    {
        $atasans = Atasan::select('nama','nomor_wa')->latest()->get();
        $jaksas = Jaksa::select('nama','nomor_wa')->latest()->get();
        $saksis = Saksi::select('nama')->latest()->get();
        $kasuss= Kasus::select('nama')->latest()->get();
        return view('dashboard.agenda.create',compact('jaksas','saksis','kasuss','atasans'));
    }

    // fungsi store
    public function store(Request $request)
    {
        $request->validate([

            'nama_atasan' => 'required|array',
            'nama_atasan.*' => 'string',
            'nomor_atasan' => 'required|array',
            'nomor_atasan.*' => 'string',
            'nama_jaksa' => 'required|array',
            'nama_jaksa.*' => 'string',
            'nomor_jaksa' => 'required|array',
            'nomor_jaksa.*' => 'string',
            'nama_kasus' => 'required|string',
            'nama_saksi' => 'required|array',
            'nama_saksi.*' => 'string',
            'nama_kasus' => 'string',
            'pesan' => 'required|string',
            'tanggal_waktu' => 'required|date',
        ]);
    
        // Map the request data to the database columns
        $reminderData = [
            'user_id' => Auth::user()->id,
            'lokasi' => Auth::user()->kejari_nama,
            'nama_kasus' => $request->input('nama_kasus'),
            'pesan' => $request->input('pesan'),
            'tanggal_waktu' => $request->input('tanggal_waktu'),
            'nama_atasan' => json_encode($request->input('nama_atasan')),
            'nomor_atasan' => json_encode($request->input('nomor_atasan')),
            'nama_jaksa' => json_encode($request->input('nama_jaksa')),
            'nomor_jaksa' => json_encode($request->input('nomor_jaksa')),
            'nama_saksi' => json_encode($request->input('nama_saksi')),
        ];
    
        // Create the reminder record
        Reminder::create($reminderData);

        return redirect('dashboard/agenda')->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_atasan' => 'required|array',
            'nama_atasan.*' => 'string',
            'nomor_atasan' => 'required|array',
            'nomor_atasan.*' => 'string',
            'nama_jaksa' => 'required|array',
            'nama_jaksa.*' => 'string',
            'nomor_jaksa' => 'required|array',
            'nomor_jaksa.*' => 'string',
            'nama_kasus' => 'required|string',
            'nama_saksi' => 'required|array',
            'nama_saksi.*' => 'string',
            'pesan' => 'required|string',
            'tanggal_waktu' => 'required|date',
        ]);

        // Find the reminder by ID
        $reminder = Reminder::findOrFail($id);

        // Map the request data to the database columns
        $reminderData = [
            'nama_kasus' => $request->input('nama_kasus'),
            'pesan' => $request->input('pesan'),
            'tanggal_waktu' => $request->input('tanggal_waktu'),
            'nama_atasan' => json_encode($request->input('nama_atasan')),
            'nomor_atasan' => json_encode($request->input('nomor_atasan')),
            'nama_jaksa' => json_encode($request->input('nama_jaksa')),
            'nomor_jaksa' => json_encode($request->input('nomor_jaksa')),
            'nama_saksi' => json_encode($request->input('nama_saksi')),
        ];

        // Update the reminder record
        $reminder->update($reminderData);

        return redirect('dashboard/agenda')->with('success', 'Agenda berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reminder = Reminder::findOrFail($id);

        if (!$reminder) {
            return abort(404);
        }

        $reminder->delete();

        return redirect('dashboard/agenda')->with('success', 'Agenda berhasil dihapus.');

    }
}
