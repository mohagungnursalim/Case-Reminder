<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reminders = Reminder::latest()->simplePaginate(10);
        return view('dashboard.agenda.index', compact('reminders'));
    }

    /**
     * Show the form for creating a new resource.
     */

    // halaman form
    public function create()
    {
        return view('dashboard.agenda.create');
    }

    // fungsi store
    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'prosecutor_name' => 'required',
            'case_name' => 'required',
            'witnesses' => 'required',
            'message' => 'required',
            'scheduled_time' => 'required|date',
        ]);

        Reminder::create($request->all());

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
