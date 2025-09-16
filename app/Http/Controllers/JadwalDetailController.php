<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\JadwalDetail;
use App\Models\JamMapel;
use App\Models\Mapel;
use App\Models\Guru;
use Illuminate\Http\Request;

class JadwalDetailController extends Controller
{
    public function index(Jadwal $jadwal)
    {
        // Ambil shift yang dipakai jadwal
        $shift = $jadwal->shifts()->first();

        if (!$shift) {
            return redirect()->back()->with('error', 'Shift belum dipilih untuk jadwal ini.');
        }

        // Semua jam_mapel dari shift tersebut
        $jamMapels = $shift->jamMapels()->orderBy('nomor_jam')->get();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $details = $jadwal->details()
            ->with(['mapel', 'guru', 'jamMapel'])
            ->get()
            ->groupBy(fn($d) => $d->hari);

        // isi default kosong untuk hari yang belum ada
        foreach ($days as $day) {
            if (!isset($details[$day])) {
                $details[$day] = collect();
            }
        }

        return view('jadwal_detail.index', compact('jadwal', 'shift', 'jamMapels', 'details'));
    }

    public function create(Jadwal $jadwal, Request $request)
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('jadwal_detail.create', [
            'jadwal' => $jadwal,
            'mapels' => $mapels,
            'gurus' => $gurus,
            'hari' => $request->get('hari'),
            'jam_mapel_id' => $request->get('jam_mapel_id'),
        ]);
    }

    public function store(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'guru_id' => 'required|exists:gurus,id',
            'hari' => 'required|string',
            'jam_mapel_id' => 'required|exists:jam_mapels,id',
        ]);

        // Cegah duplikat: satu jam_mapel + hari hanya bisa dipakai sekali
        $exists = JadwalDetail::where('jadwal_id', $jadwal->id)
            ->where('hari', $request->hari)
            ->where('jam_mapel_id', $request->jam_mapel_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'jam_mapel_id' => 'Jam pelajaran ini sudah diisi pada hari yang dipilih.',
            ])->withInput();
        }

        $jadwal->details()->create($request->only(['mapel_id', 'guru_id', 'hari', 'jam_mapel_id']));

        return redirect()->route('guru-piket.jadwal.details.index', $jadwal->id)
            ->with('success', 'Detail jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal, JadwalDetail $detail)
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('jadwal_detail.edit', compact('jadwal', 'detail', 'mapels', 'gurus'));
    }

    public function update(Request $request, Jadwal $jadwal, JadwalDetail $detail)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'guru_id' => 'required|exists:gurus,id',
            'hari' => 'required|string',
            'jam_mapel_id' => 'required|exists:jam_mapels,id',
        ]);

        $detail->update($request->only(['mapel_id', 'guru_id', 'hari', 'jam_mapel_id']));

        return redirect()->route('guru-piket.jadwal.details.index', $jadwal->id)
            ->with('success', 'Detail jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal, JadwalDetail $detail)
    {
        $detail->delete();

        return redirect()->route('guru-piket.jadwal.details.index', $jadwal->id)
            ->with('success', 'Detail jadwal berhasil dihapus.');
    }
}
