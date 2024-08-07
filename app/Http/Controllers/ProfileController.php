<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(): View
    {
        $user = auth()->user();

        return view('profile.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->fill($data);

        if (array_key_exists('email', $data) && $user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Log::channel('activity')->info('User memperbarui profil!', [
            'name' => Auth::user()->name,
            'lokasi' => Auth::user()->kejari_nama,
            'timestamp' => now()->toDateTimeString()
        ]);

        return redirect('/dashboard/profile')->with('success_informasi', 'Profil telah diperbarui!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(): RedirectResponse
    {
        $user = auth()->user();
        $user->delete();

        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        Log::channel('activity')->info('User menghapus akun', [
            'name' => Auth::user()->name,
            'lokasi' => Auth::user()->kejari_nama,
            'timestamp' => now()->toDateTimeString()
        ]);
        return redirect('/');
    }
}
