<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Menampilkan daftar semua kelas
    public function index()
    {
        // Global Scope multi-tenancy akan otomatis memfilter kelas
        // berdasarkan sekolah user yang login.
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();
        return view('kelas.index', compact('daftarKelas'));
    }

    // Menampilkan form untuk membuat kelas baru
    public function create()
    {
        return view('kelas.create');
    }

    // Menyimpan data kelas baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        // Trait Multitenantable akan otomatis mengisi 'sekolah_id'
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('guru-piket.kelas.index')
                         ->with('success', 'Kelas berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit kelas
    public function edit(Kelas $kela) // Laravel akan otomatis mencari data kelas berdasarkan ID
    {
        return view('kelas.edit', compact('kela'));
    }

    // Mengupdate data kelas di database
    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        $kela->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('guru-piket.kelas.index')
                         ->with('success', 'Kelas berhasil diperbarui.');
    }

    // Menghapus data kelas dari database
    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()->route('guru-piket.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}