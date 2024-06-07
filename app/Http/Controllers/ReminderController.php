<?php

namespace App\Http\Controllers;

use App\Models\Jaksa;
use App\Models\Reminder;
use App\Models\Saksi;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       // Ambil data jaksa dan saksi untuk ditampilkan di view
        $jaksas = Jaksa::latest()->get();
        $saksis = Saksi::latest()->get();

        // Membuat query builder untuk mengambil data reminder
        $query = Reminder::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $search = $request->get('search');
            // Terapkan logika pencarian ke query builder
            $query->where('nama_kasus', 'like', '%'.$search.'%')
                ->orWhere('pesan', 'like', '%'.$search.'%')
                ->orWhereJsonContains('nama_jaksa', $search)
                ->orWhereJsonContains('nomor_jaksa', $search)
                ->orWhereJsonContains('nama_saksi', $search)
                ->orWhere('tanggal_waktu', 'like', '%'.$search.'%');
        }

        // Ambil data reminder berdasarkan query yang telah dibuat
        $reminders = $query->latest()->simplePaginate(10);
        return view('dashboard.agenda.index', compact('reminders','jaksas','saksis'));
    }

    /**
     * Show the form for creating a new resource.
     */

    // halaman form
    public function create()
    {
        $jaksas = Jaksa::latest()->get();
        $saksis = Saksi::latest()->get();
        return view('dashboard.agenda.create',compact('jaksas','saksis'));
    }

    // fungsi store
    public function store(Request $request)
    {
        $request->validate([
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
    
        // Map the request data to the database columns
        $reminderData = [
            'nama_kasus' => $request->input('nama_kasus'),
            'pesan' => $request->input('pesan'),
            'tanggal_waktu' => $request->input('tanggal_waktu'),
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
