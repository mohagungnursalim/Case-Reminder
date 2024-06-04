<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotLibrarian
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
            // Cek apakah pengguna adalah pustakawan (bukan admin)
            if (Auth::check() && Auth::user()->is_admin == true) {
                // Jika pengguna adalah admin, maka redirect atau batalkan permintaan
                abort(404);
            }

        return $next($request);
    }
}
