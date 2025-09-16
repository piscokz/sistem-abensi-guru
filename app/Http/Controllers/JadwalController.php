<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Shift;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // Daftar jadwal untuk kelas tertentu
    public function index(Kelas $kelas)
    {
        $jadwals = $kelas->jadwals()->with('shifts')->get();
        return view('jadwal.index', compact('kelas', 'jadwals'));
    }

    // Form buat jadwal baru
    public function create(Kelas $kelas)
    {
        $shifts = Shift::orderBy('nama')->get();
        return view('jadwal.create', compact('kelas', 'shifts'));
    }

    // Simpan jadwal baru
    public function store(Request $request, Kelas $kelas)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
        ]);

        $jadwal = $kelas->jadwals()->create([
            'sekolah_id' => auth()->user()->sekolah_id,
            'tahun_ajaran' => now()->year,
            'is_active' => true,
            'nama_jadwal' => Shift::find($request->shift_id)->nama, // ambil nama shift
        ]);

        $jadwal->shifts()->attach($request->shift_id);

        return redirect()->route('guru-piket.kelas.jadwal.index', $kelas->id)
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }


    // Form edit jadwal
    public function edit(Kelas $kelas, Jadwal $jadwal)
    {
        $shifts = Shift::orderBy('nama')->get();
        return view('jadwal.edit', compact('kelas', 'jadwal', 'shifts'));
    }

    // Update jadwal
    public function update(Request $request, Kelas $kelas, Jadwal $jadwal)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
        ]);

        $jadwal->update([
            'is_active' => $request->boolean('is_active'),
            'nama_jadwal' => Shift::find($request->shift_id)->nama, // update sesuai shift
        ]);

        $jadwal->shifts()->sync($request->shift_id);

        return redirect()->route('guru-piket.kelas.jadwal.index', $kelas->id)
            ->with('success', 'Jadwal berhasil diperbarui.');
    }


    // Hapus jadwal
    public function destroy(Kelas $kelas, Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('guru-piket.kelas.jadwal.index', $kelas->id)
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
