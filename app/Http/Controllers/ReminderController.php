<?php

namespace App\Http\Controllers;

use App\Models\Atasan;
use App\Models\Jaksa;
use App\Models\Kasus;
use App\Models\Reminder;
use App\Models\Saksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan pengguna yang sedang login
        $user = Auth::user();
        $loggedInUserId = $user->id;


        // Logika berdasarkan peran pengguna
        if ($user->email === 'mohagungnursalim@gmail.com') {
            // Super Admin: tampilkan semua data
            $atasans = Atasan::select('nama', 'nomor_wa')->oldest()->get();
            $jaksas = Jaksa::select('nama', 'nomor_wa')->oldest()->get();
            $saksis = Saksi::select('nama')->oldest()->get();
            $kasuss = Kasus::select('nama')->oldest()->get();
        } elseif ($user->is_admin) {
            // Admin: tampilkan semua data pada lokasi Kejari tertentu
            $atasans = Atasan::select('nama', 'nomor_wa')->where('lokasi', $user->kejari_nama)->oldest()->get();
            $jaksas = Jaksa::select('nama', 'nomor_wa')->where('lokasi', $user->kejari_nama)->oldest()->get();
            $saksis = Saksi::select('nama')->where('lokasi', $user->kejari_nama)->oldest()->get();
            $kasuss = Kasus::select('nama')->where('lokasi', $user->kejari_nama)->oldest()->get();
        } else {
            // Operator: tampilkan hanya data mereka sendiri
            $atasans = Atasan::select('nama', 'nomor_wa')->where('user_id', $loggedInUserId)->oldest()->get();
            $jaksas = Jaksa::select('nama', 'nomor_wa')->where('user_id', $loggedInUserId)->oldest()->get();
            $saksis = Saksi::select('nama')->where('user_id', $loggedInUserId)->oldest()->get();
            $kasuss = Kasus::select('nama')->where('user_id', $loggedInUserId)->oldest()->get();
        }

        // Membuat query builder untuk mengambil data reminder
    $query = Reminder::latest();

    // Logika berdasarkan peran pengguna untuk data reminder
    if ($user->email === 'mohagungnursalim@gmail.com') {
        // Super Admin: tampilkan semua data
        
    } elseif ($user->is_admin) {
        // Admin: tampilkan semua data pada lokasi Kejari tertentu
        $query->where('lokasi', $user->kejari_nama);
    } else {
        // Operator: tampilkan hanya data mereka sendiri
        $query->where('user_id', $loggedInUserId);
    }

    // Pencarian berdasarkan query 'search'
    $search = $request->query('search');
    if ($search) {
        $query->where(function($query) use ($search) {
            $query->where('nama_kasus', 'LIKE', "%{$search}%")
                ->orWhere('pesan', 'LIKE', "%{$search}%")
                ->orWhere('tanggal_waktu', 'LIKE', "%{$search}%")
                ->orWhere('lokasi', 'LIKE', "%{$search}%");
        });
    }

    // Ambil data reminder berdasarkan query yang telah dibuat lalu paginasi perbaris (10)
    $reminders = $query->paginate(10);
        // Mengirim data ke view
        return view('dashboard.agenda.index', compact('reminders', 'jaksas', 'saksis', 'kasuss', 'atasans'));
    }

    /**
     * Show the form for creating a new resource.
     */

    // halaman form
    public function create()
    {
        // Mendapatkan pengguna yang sedang login
        $user = Auth::user();
        $loggedInUserId = $user->id;

        // Logika berdasarkan peran pengguna
        if ($user->email === 'mohagungnursalim@gmail.com') {
            // Super Admin: tampilkan semua data
            $atasans = Atasan::select('nama', 'nomor_wa')->oldest()->get();
            $jaksas = Jaksa::select('nama', 'nomor_wa')->oldest()->get();
            $saksis = Saksi::select('nama')->oldest()->get();
            $kasuss = Kasus::select('nama')->oldest()->get();
        } elseif ($user->is_admin) {
            // Admin: tampilkan semua data pada lokasi Kejari tertentu
            $atasans = Atasan::select('nama', 'nomor_wa')->where('lokasi', $user->kejari_nama)->oldest()->get();
            $jaksas = Jaksa::select('nama', 'nomor_wa')->where('lokasi', $user->kejari_nama)->oldest()->get();
            $saksis = Saksi::select('nama')->where('lokasi', $user->kejari_nama)->oldest()->get();
            $kasuss = Kasus::select('nama')->where('lokasi', $user->kejari_nama)->oldest()->get();
        } else {
            // Operator: tampilkan hanya data mereka sendiri
            $atasans = Atasan::select('nama', 'nomor_wa')->where('user_id', $loggedInUserId)->oldest()->get();
            $jaksas = Jaksa::select('nama', 'nomor_wa')->where('user_id', $loggedInUserId)->oldest()->get();
            $saksis = Saksi::select('nama')->where('user_id', $loggedInUserId)->oldest()->get();
            $kasuss = Kasus::select('nama')->where('user_id', $loggedInUserId)->oldest()->get();
        }

        // Mengirim data ke view
        return view('dashboard.agenda.create', compact('jaksas', 'saksis', 'kasuss', 'atasans'));
    }


    // fungsi store
    public function store(Request $request)
    {
        // Determine lokasi based on the user role
        if (Auth::user()->email == 'mohagungnursalim@gmail.com') {
            $lokasi = $request->input('lokasi'); // Adjusted to match the form field name
        } else {
            $lokasi = Auth::user()->kejari_nama;
        }
    
        // Validasi input
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
            'nama_kasus.*' => 'string',
            'nama_saksi' => 'required|array',
            'nama_saksi.*' => 'string',
            'pesan' => 'required|string',
            'tanggal_waktu' => 'required|date',
            'lokasi' => 'nullable|string'
        ]);
    
        // Map request data ke kolom database
        $reminderData = [
            'user_id' => Auth::user()->id,
            'lokasi' => $lokasi,
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
