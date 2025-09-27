<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\JadwalDetail;
use App\Models\DetailJamMapel;
use App\Models\JamMapel;
use App\Models\Mapel;
use App\Models\Guru;
use Illuminate\Http\Request;

class JadwalDetailController extends Controller
{
    public function index(Jadwal $jadwal)
    {
        $jadwal->load('kelas', 'shift');

        $shift = $jadwal->shift()->first();
        if (!$shift) {
            return redirect()->back()->with('error', 'Shift belum dipilih untuk jadwal ini.');
        }

        $jamMapels = $shift->jamMapels()->orderBy('nomor_jam')->get();

        // Ambil detail jadwal per hari
        $detailJamMapels = DetailJamMapel::with(['jadwalDetail.mapel', 'jadwalDetail.guru', 'jamMapel'])
            ->where('jadwal_id', $jadwal->id)
            ->get()
            ->groupBy(fn($d) => $d->jadwalDetail->hari);

        // Bentuk ulang jadi slot siap render
        $groupedDetails = collect(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])->mapWithKeys(function ($hari) use ($detailJamMapels, $jamMapels) {
            $dayDetails = $detailJamMapels->get($hari, collect())
                ->sortBy(fn($d) => $d->jamMapel->nomor_jam)
                ->values();

            $groups = [];
            $i = 0;
            $totalSlot = $jamMapels->count();

            while ($i < $totalSlot) {
                $currentJam = $jamMapels[$i];
                $detail = $dayDetails->firstWhere('jam_mapel_id', $currentJam->id);

                $colspan = 1;
                $j = $i + 1;

                if ($detail) {
                    // gabung kalau mapel & guru sama
                    while (
                        $j < $totalSlot &&
                        ($nextDetail = $dayDetails->firstWhere('jam_mapel_id', $jamMapels[$j]->id)) &&
                        $nextDetail->mapel_id == $detail->mapel_id &&
                        $nextDetail->guru_id == $detail->guru_id
                    ) {
                        $colspan++;
                        $j++;
                    }
                    $groups[] = [
                        'type' => 'detail',
                        'detail' => $detail,
                        'colspan' => $colspan,
                    ];
                } else {
                    $groups[] = [
                        'type' => 'empty',
                        'jam_id' => $currentJam->id,
                        'colspan' => 1,
                    ];
                    $j = $i + 1;
                }

                $i = $j;
            }

            return [$hari => $groups];
        });

        return view('jadwal_detail.index', [
            'jadwal' => $jadwal,
            'jamMapels' => $jamMapels,
            'groupedDetails' => $groupedDetails,
        ]);
    }


    public function create(Jadwal $jadwal)
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('jadwal_detail.create', compact('jadwal', 'mapels', 'gurus'));
    }

    public function createWithJam(Jadwal $jadwal, JamMapel $jamMapel, $hari)
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('jadwal_detail.createWithJam', [
            'jadwal' => $jadwal,
            'mapels' => $mapels,
            'gurus' => $gurus,
            'hari' => $hari,
            'jamMapel' => $jamMapel,
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
        // $exists = JadwalDetail::where('jadwal_id', $jadwal->id)
        //     ->where('hari', $request->hari)
        //     ->where('jam_mapel_id', $request->jam_mapel_id)
        //     ->exists();

        $exists = DetailJamMapel::where('jadwal_id', $jadwal->id)
            ->whereHas('jadwalDetail', function ($query) use ($request) {
                $query->where('hari', $request->hari);
            })
            ->where('jam_mapel_id', $request->jam_mapel_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'jam_mapel_id' => 'Jam pelajaran ini sudah diisi pada hari yang dipilih.',
            ])->withInput();
        }

        $jadwal->details()->create($request->only(['mapel_id', 'guru_id', 'hari']));
        DetailJamMapel::create([
            'jadwal_id' => $jadwal->id,
            'jadwal_detail_id' => $jadwal->details()->latest()->first()->id,
            'jam_mapel_id' => $request->jam_mapel_id,
        ]);

        return redirect()->route('guru-piket.jadwal.details.index', $jadwal->id)
            ->with('success', 'Detail jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal, JadwalDetail $detail)
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $gurus = Guru::orderBy('nama_guru')->get();
        $detailJamMapel = DetailJamMapel::where('jadwal_detail_id', $detail->id)->first();
        $hari = $detail->hari;
        $jamMapel = $detailJamMapel->jamMapel;
        // dd($jamMapel);

        return view('jadwal_detail.edit', [
            'jadwal' => $jadwal,
            'mapels' => $mapels,
            'gurus' => $gurus,
            'hari' => $hari,
            'detail' => $detail,
            'jamMapel' => $jamMapel,
        ]);
    }

    public function update(Request $request, Jadwal $jadwal, JadwalDetail $detail)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'guru_id' => 'required|exists:gurus,id',
            'hari' => 'required|string',
            'jam_mapel_id' => 'required|exists:jam_mapels,id',
        ], [
            'mapel_id.required' => 'Mata pelajaran wajib dipilih.',
            'mapel_id.exists' => 'Mata pelajaran tidak valid.',
            'guru_id.required' => 'Guru wajib dipilih.',
            'guru_id.exists' => 'Guru tidak valid.',
            'hari.required' => 'Hari wajib diisi.',
            'jam_mapel_id.required' => 'Jam pelajaran wajib dipilih.',
            'jam_mapel_id.exists' => 'Jam pelajaran tidak valid.',
        ]);

        $detail->update($request->only(['mapel_id', 'guru_id', 'hari']));

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
