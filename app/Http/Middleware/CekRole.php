<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user tidak login atau tidak punya role yang diizinkan
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Lemparkan ke halaman 403 (Forbidden)
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        return $next($request);
    }
}