<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::latest()->get();
        return view('guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', 'unique:gurus'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        

        // Gunakan transaction untuk memastikan semua data tersimpan atau tidak sama sekali
        DB::transaction(function () use ($request) {
            // 1. Buat User baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'guru_mapel',
            ]);

            // 2. Buat Guru baru
            Guru::create([
                'user_id' => $user->id,
                'nama_guru' => $request->name,
                'nip' => $request->nip,
            ]);
        });

        return redirect()->route('guru-piket.guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', 'unique:gurus,nip,' . $guru->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $guru->user_id],
        ]);

        DB::transaction(function () use ($request, $guru) {
            // 1. Update data di tabel User
            $guru->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // 2. Update data di tabel Guru
            $guru->update([
                'nama_guru' => $request->name,
                'nip' => $request->nip,
            ]);

            // Jika password diisi, update password
            if ($request->filled('password')) {
                $request->validate([
                    'password' => ['confirmed', Rules\Password::defaults()],
                ]);
                $guru->user->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        });

        return redirect()->route('guru-piket.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function show(Guru $guru)
    {
        return view('guru.show', compact('guru'));
    }

    public function destroy(Guru $guru)
    {
        // Menggunakan transaction karena akan menghapus dari 2 tabel
        DB::transaction(function () use ($guru) {
            $guru->user->delete(); // Hapus user, guru akan terhapus otomatis via cascade
        });

        return redirect()->route('guru-piket.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
