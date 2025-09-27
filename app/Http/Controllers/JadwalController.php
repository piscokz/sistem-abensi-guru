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
        $jadwals = $kelas->jadwals()->with('shift')->get();
        // dd($jadwals);
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
            // pastikan shift_id ada di tabel shifts dan shift belum dipakai di jadwal kelas ini
            'shift_id' => 'required|exists:shifts,id|unique:jadwals,shift_id,NULL,id,kelas_id,' . $kelas->id,
            // 'shift_id' => 'required|exists:shifts,id',
        ]);

        Jadwal::create([
            'sekolah_id' => $kelas->sekolah_id,
            'kelas_id' => $kelas->id,
            'shift_id' => $request->shift_id,
            'nama_jadwal' => Shift::find($request->shift_id)->nama, // ambil nama shift
        ]);

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
            // pastikan shift_id ada di tabel shifts dan jika update shift_id, pastikan unik di jadwals kecuali jadwal ini
            'shift_id' => 'required|exists:shifts,id|unique:jadwals,shift_id,' . $jadwal->id . ',id,kelas_id,' . $kelas->id,
        ]);

        $jadwal->update([
            'nama_jadwal' => Shift::find($request->shift_id)->nama, // update sesuai shift
        ]);

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
