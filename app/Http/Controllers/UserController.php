<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loggedInUserId = auth()->id();
        $loggedInUserEmail = auth()->user()->email;
        $loggedInUserIsAdmin = auth()->user()->is_admin;

        $query = User::query();

        if ($loggedInUserIsAdmin && $loggedInUserEmail == 'mohagungnursalim@gmail.com') {
            // Super Admin dapat melihat semua data
            $query->where('id', '!=', $loggedInUserId);
        } elseif ($loggedInUserIsAdmin) {
            // Admin hanya dapat melihat data Operator
            $query->where('is_admin', false)
                ->where('id', '!=', $loggedInUserId);
        } else {
            // Operator tidak dapat melihat data admin lain dan data diri sendiri
            $query->where('id', '!=', $loggedInUserId);
        }

        if (request('search')) {
            $searchTerm = request('search');
            $query->where(function($query) use ($searchTerm, $loggedInUserIsAdmin, $loggedInUserId) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('kejari_nama', 'like', '%' . $searchTerm . '%');
                
                // Pastikan pengguna tidak melihat data mereka sendiri saat mencari
                $query->where('id', '!=', $loggedInUserId);

                if ($loggedInUserIsAdmin) {
                    // Admin hanya dapat melihat data Operator saat melakukan pencarian
                    $query->where('is_admin', false);
                }
            });
        }

        $users = $query->oldest()->cursorPaginate(10)->withQueryString();

        foreach ($users as $user) {
            $user->is_online = Cache::has('user-is-online-' . $user->id);
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
            'is_admin' => 'required|in:0,1',
            'kejari_nama' => 'required|in:Kejari Sulteng,Kejari Palu,Kejari Poso,Kejari Tolitoli,Kejari Banggai,Kejari Parigi,Kejari Donggala,Kejari Buol,Kejari Morowali'
        ]);

        
        $userData = $request->only(['email', 'name', 'is_admin', 'kejari_nama']);
        $userData['last_seen'] = null; // Atur nilai last_seen menjadi null
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

    public function peran(Request $request, string $id)
    {
        $request->validate([
            'is_admin' => 'required|in:0,1'
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'is_admin' => $request->input('is_admin')
        ]);

        return redirect('/dashboard/user')->with('success', 'Peran berhasil diubah!');
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
