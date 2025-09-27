<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $role = Auth::user()->role;

        // return match ($role) {
        //     'kurikulum' => redirect()->intended('/dashboard'),
        //     'guru_piket' => redirect()->intended('/dashboard'),
        //     'guru_mapel'              => redirect()->intended('/'),
        //     'kelas'                   => redirect()->intended('/jadwal'),
        //     default                   => redirect()->intended('/session-default'),
        // };

        // paksa redirect ke halaman tertentu berdasarkan role
        return match ($role) {
            'kurikulum'   => redirect()->to('/dashboard'),
            'guru_piket'  => redirect()->to('/dashboard'),
            'guru_mapel'  => redirect()->to('/'),
            'kelas_siswa' => redirect()->to('/jadwal_kelas'),
            default       => redirect()->to('/session-default'),
        };
    }

    /**
     * Proses logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
