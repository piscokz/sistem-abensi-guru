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
            'name' => $request->nama_kelas,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kelas_siswa',
        ]);

        // buat kelas
        Kelas::create([
            'user_id' => $user->id,
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('guru-piket.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan beserta akun login.');
    }

    public function edit(Kelas $kela)
    {
        return view('kelas.edit', compact('kela'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($kela->user_id)],
            'password' => 'nullable|min:6|confirmed',
        ]);
        
        // update user akun kelas
        $kela->user->update([
            'name' => $request->nama_kelas,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $kela->user->password,
        ]);

        // update kelas
        $kela->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('guru-piket.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        // hapus user terkait
        $kela->user()->delete();
        $kela->delete();

        return redirect()->route('guru-piket.kelas.index')
            ->with('success', 'Kelas dan akun login berhasil dihapus.');
    }
}
