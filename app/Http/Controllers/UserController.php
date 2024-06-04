<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loggedInUserId = auth()->id();
        if (request('search')) {
            $users = User::where(function($query) {
                            $searchTerm = request('search');
                            $query->where('name', 'like', '%' . $searchTerm . '%')
                                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
                        })
                        ->where('id', '!=', $loggedInUserId)
                        ->oldest()
                        ->cursorPaginate(10)
                        ->withQueryString();
        } else {
            $users = User::where('id', '!=', $loggedInUserId)
                        ->oldest()
                        ->cursorPaginate(10)
                        ->withQueryString();
        }

        return view('dashboard.user.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'is_admin' => 'required|in:0,1'
        ]);

        $userData = $request->only(['email', 'name', 'is_admin']);
        $userData['password'] = Hash::make('12345678');
        User::create($userData);

        return redirect('/dashboard/user')->with('success', 'Akun baru berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make('12345678')
        ]);

        return redirect('/dashboard/user')->with('success', 'Password telah direset menjadi password default!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/dashboard/user')->with('success', 'Akun berhasil dihapus!');
    }
}
