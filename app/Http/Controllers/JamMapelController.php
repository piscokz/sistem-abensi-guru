<?php

namespace App\Http\Controllers;

use App\Models\JamMapel;
use App\Models\Shift;
use Illuminate\Http\Request;

class JamMapelController extends Controller
{
    /**
     * Tampilkan semua jam mapel dari shift tertentu.
     */
    public function index(Shift $shift)
    {
        $jamMapels = $shift->jamMapels()
            ->orderBy('nomor_jam')
            ->get();

        return view('jam_mapel.index', compact('shift', 'jamMapels'));
    }

    /**
     * Form tambah jam mapel.
     */
    public function create(Shift $shift)
    {
        $jamMapel = new JamMapel();
        return view('jam_mapel.create', compact('shift', 'jamMapel'));
    }

    /**
     * Simpan jam mapel baru.
     */
    public function store(Request $request, Shift $shift)
    {
        $request->validate([
            'nomor_jam'   => 'required|integer|min:1',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $shift->jamMapels()->create([
            'shift_id'   => $shift->id,
            'nomor_jam'  => $request->nomor_jam,
            'jam_mulai'  => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('guru-piket.shift.jam-mapel.index', ['shift' => $shift->id])
            ->with('success', 'Jam Mapel berhasil ditambahkan.');
    }

    /**
     * Form edit jam mapel.
     */
    public function edit(JamMapel $jamMapel)
    {
        $shift = $jamMapel->shift;
        return view('jam_mapel.edit', compact('shift', 'jamMapel'));
    }

    /**
     * Update jam mapel.
     */
    public function update(Request $request, JamMapel $jamMapel)
    {
        $request->validate([
            'nomor_jam'   => 'required|integer|min:1',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $jamMapel->update([
            'nomor_jam'  => $request->nomor_jam,
            'jam_mulai'  => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('guru-piket.shift.jam-mapel.index', ['shift' => $jamMapel->shift_id])
            ->with('success', 'Jam Mapel berhasil diperbarui.');
    }

    /**
     * Hapus jam mapel.
     */
    public function destroy(JamMapel $jamMapel)
    {
        $shiftId = $jamMapel->shift_id;
        $jamMapel->delete();

        return redirect()
            ->route('guru-piket.shift.jam-mapel.index', ['shift' => $shiftId])
            ->with('success', 'Jam Mapel berhasil dihapus.');
    }
}
