<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $expiresAt = now()->addMinutes(1); /* keep online for 1 min */
            Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);

            /* last seen */
            User::where('id', Auth::user()->id)->update(['last_seen' => now()]);
        }

        return $next($request);
    }
}
