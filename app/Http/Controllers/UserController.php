<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        $kejariNama = auth()->user()->kejari_nama;

        $query = User::query();

        if ($loggedInUserIsAdmin && $loggedInUserEmail == 'mohagungnursalim@gmail.com') {
            // Super Admin dapat melihat semua data
            $query->where('id', '!=', $loggedInUserId);
        } elseif ($loggedInUserIsAdmin) {
            // Admin hanya dapat melihat data Operator dan jika lokasi kejari sama 
            $query->where('is_admin', false)
                ->where('id', '!=', $loggedInUserId)
                ->where('kejari_nama', $kejariNama);
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
            });
        }

        // Gunakan paginate() alih-alih cursorPaginate()
        $users = $query->oldest()->paginate(10)->withQueryString();

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
            'kejari_nama' => 'required|in:Kejati Sulteng,Kejari Palu,Kejari Poso,Kejari Tolitoli,Kejari Banggai,Kejari Parigi,Kejari Donggala,Kejari Buol,Kejari Morowali'
        ]);

        
        $userData = $request->only(['email', 'name', 'is_admin', 'kejari_nama']);
        $userData['last_seen'] = null; // Atur nilai last_seen menjadi null
        $userData['password'] = Hash::make('12345678');
        User::create($userData);

        Log::channel('activity')->info('Menambahkan akun baru', [
            'name' => Auth::user()->name, //nama user yang melakukan penambahan akun
            'email_user'=> $request->input('email'), //email akun yang ditambahkan
            'lokasi' => $request->input('kejari_nama'), //lokasi akun yang ditambahkan
            'timestamp' => now()->toDateTimeString() //waktu saat akun ditambahkan
        ]);
        return redirect('/dashboard/user')->with('success', 'Akun baru berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($user->name)
        ]);

        Log::channel('activity')->info('Mereset password akun', [
            'name' => Auth::user()->name, //nama user yang melakukan reset password
            'email_user'=> $user->email, //email akun yang direset passwordnya
            'lokasi' => Auth::user()->kejari_nama, //lokasi user yang mereset password akun
            'timestamp' => now()->toDateTimeString() //waktu saat dilakukan reset
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

        Log::channel('activity')->info('Memperbarui peran akun', [
            'name' => Auth::user()->name, //nama user yang melakukan pembaruan
            'email_user'=> $user->email, //email akun yang diperbarui perannya
            'lokasi' => Auth::user()->kejari_nama, //lokasi user yang memperbarui peran akun
            'timestamp' => now()->toDateTimeString() //waktu saat dilakukan pembaruan
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

        Log::channel('activity')->info('Menghapus akun', [
            'name' => Auth::user()->name, //nama user yang melakukan penghapusan
            'email_user'=> $user->email, //email akun yang dihapus
            'lokasi' => Auth::user()->kejari_nama, //lokasi user yang menghapus akun
            'timestamp' => now()->toDateTimeString() //waktu saat dilakukan penghapusan
        ]);
        return redirect('/dashboard/user')->with('success', 'Akun berhasil dihapus!');
    }
}
