<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // List semua jadwal induk
    public function index()
    {
        $jadwals = Jadwal::with('kelas')->orderByDesc('created_at')->get();
        return view('jadwal.index', compact('jadwals'));
    }

    // Form tambah jadwal
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('jadwal.create', compact('kelas'));
    }

    // Simpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        Jadwal::create([
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran' => $request->tahun_ajaran,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('guru-piket.jadwal.index')
            ->with('success', 'Jadwal berhasil dibuat.');
    }

    // Form edit jadwal
    public function edit(Jadwal $jadwal)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('jadwal.edit', compact('jadwal', 'kelas'));
    }

    // Update jadwal
    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $jadwal->update([
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran' => $request->tahun_ajaran,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('guru-piket.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    // Hapus jadwal
    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('guru-piket.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
