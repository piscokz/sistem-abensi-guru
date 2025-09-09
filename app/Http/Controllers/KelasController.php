<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index()
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();
        return view('kelas.index', compact('daftarKelas'));
    }

    public function create()
    {
        return view('kelas.create', ['kelas' => new Kelas()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // buat user untuk akun kelas
        $user = User::create([
            'nama' => $request->nama_kelas,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kelas',
        ]);

        // buat kelas
        Kelas::create([
            'user_id' => $user->id,
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('guru-piket.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan beserta akun login.');
    }

    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($kelas->user_id)],
            'password' => 'nullable|min:6|confirmed',
        ]);

        // update user akun kelas
        $kelas->user->update([
            'nama' => $request->nama_kelas,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $kelas->user->password,
        ]);

        // update kelas
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('guru-piket.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        // hapus user terkait
        $kelas->user()->delete();
        $kelas->delete();

        return redirect()->route('guru-piket.kelas.index')
            ->with('success', 'Kelas dan akun login berhasil dihapus.');
    }
}
