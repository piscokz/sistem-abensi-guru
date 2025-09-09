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
        // Asumsi Anda punya scope di model Guru untuk filter sekolah
        $gurus = Guru::with('mapels')->latest()->get();
        return view('guru.index', compact('gurus'));
    }

    public function create()
    {
        // Ambil semua mapel untuk ditampilkan di form
        $mapels = Mapel::orderBy('nama_mapel')->get();
        return view('guru.create', compact('mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', 'unique:gurus'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'mapels' => ['required', 'array'], // Pastikan mapels adalah array
            'mapels.*' => ['exists:mapels,id'], // Pastikan setiap item di array ada di tabel mapels
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
            $guru = Guru::create([
                'user_id' => $user->id,
                'nama_guru' => $request->name,
                'nip' => $request->nip,
            ]);

            // 3. Hubungkan Guru dengan Mapel menggunakan sync()
            $guru->mapels()->sync($request->mapels);
        });

        return redirect()->route('guru-piket.guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        // Ambil ID mapel yang sudah dimiliki guru untuk pre-select di form
        $guruMapelIds = $guru->mapels->pluck('id')->toArray();

        return view('guru.edit', compact('guru', 'mapels', 'guruMapelIds'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', 'unique:gurus,nip,' . $guru->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $guru->user_id],
            'mapels' => ['required', 'array'],
            'mapels.*' => ['exists:mapels,id'],
        ]);

        DB::transaction(function () use ($request, $guru) {
            // 1. Update data di tabel User
            $guru->user->update([
                'nama' => $request->nama,
                'email' => $request->email,
            ]);

            // 2. Update data di tabel Guru
            $guru->update([
                'nama' => $request->nama,
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

            // 3. Update relasi guru dengan mapel
            $guru->mapels()->sync($request->mapels);
        });

        return redirect()->route('guru-piket.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function show(Guru $guru)
    {
        // Muat relasi mapels untuk ditampilkan
        $guru->load('mapels');
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
