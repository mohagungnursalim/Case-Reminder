<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        Log::channel('activity')->info('User memperbarui password!', [
            'name' => Auth::user()->name,
            'lokasi' => Auth::user()->kejari_nama,
            'timestamp' => now()->toDateTimeString()
        ]);
        
        return redirect('/dashboard/profile')->with('success_password', 'Password telah diperbarui!');
    }
}
