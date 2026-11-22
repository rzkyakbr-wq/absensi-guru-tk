<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role pengguna sesuai dengan parameter yang diizinkan
        if (!in_array(Auth::user()->role, $roles)) {
            // Jika role tidak sesuai, kembalikan ke dashboard masing-masing
            return Auth::user()->role === 'guru'
                ? redirect()->route('guru.dashboard')
                : redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}