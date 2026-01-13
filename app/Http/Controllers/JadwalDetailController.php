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
        $namaKelas = $jadwal->kelas->nama_kelas;

        // $jadwalDetail = JadwalDetail::where('jadwal_id', $jadwal->id)->get()->groupBy('hari');
        // dd($jadwalDetail);

        $jadwalDetail = JadwalDetail::where('jadwal_id', $jadwal->id)
            ->with(['mapel', 'guru', 'jamMapel'])
            ->get()
            ->groupBy('hari');

        // urutan hari yang diinginkan
        $order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // sort collection sesuai urutan manual
        $jadwalDetail = $jadwalDetail->sortBy(function ($value, $key) use ($order) {
            return array_search($key, $order);
        });



        $jamMapel = JamMapel::where('shift_id', $jadwal->shift->id)->orderBy('nomor_jam')->get();
        // dd($jamMapel);

        return view('jadwal_detail.index', compact('jadwal', 'namaKelas', 'jadwalDetail', 'jamMapel'));
    }

    // public function index(Jadwal $jadwal)
    // {
    //     $jadwal->load('kelas', 'shift');

    //     $namaKelas = $jadwal->kelas->nama_kelas;

    //     $shift = $jadwal->shift;
    //     if (!$shift) {
    //         return redirect()->back()->with('error', 'Shift belum dipilih untuk jadwal ini.');
    //     }

    //     // Ambil semua slot jam sesuai shift
    //     $jamMapels = JamMapel::where('shift_id', $shift->id)
    //         ->orderBy('nomor_jam')
    //         ->get();

    //     // Ambil semua detail per jam
    //     $detailJamMapels = DetailJamMapel::with(['jadwalDetail.mapel', 'jadwalDetail.guru', 'jamMapel'])
    //         ->where('jadwal_id', $jadwal->id)
    //         ->get();

    //     // Bentuk struktur Hari -> Nomor Jam -> Detail
    //     $detailsByDay = [];
    //     foreach ($detailJamMapels as $detailJam) {
    //         $hari = $detailJam->jadwalDetail->hari;
    //         $jamId = $detailJam->jam_mapel_id; // pakai id

    //         $detailsByDay[$hari][$jamId] = [
    //             'mapel' => $detailJam->jadwalDetail->mapel,
    //             'guru' => $detailJam->jadwalDetail->guru,
    //         ];
    //     }


    //     // Urutkan slot per hari biar rapih
    //     foreach ($detailsByDay as $hari => $slots) {
    //         ksort($detailsByDay[$hari]);
    //     }

    //     return view('jadwal_detail.index', [
    //         'jadwal' => $jadwal,
    //         'namaKelas' => $namaKelas,
    //         'jamMapels' => $jamMapels,
    //         'detailsByDay' => $detailsByDay,
    //     ]);
    // }


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

        // Buat jadwal detail terlebih dahulu
        $jadwalDetail = $jadwal->details()->create($request->only(['mapel_id', 'guru_id', 'hari', 'jadwal_id', 'jam_mapel_id']));

        // Kemudian buat detail jam mapel
        DetailJamMapel::create([
            'jadwal_id' => $jadwal->id,
            'jadwal_detail_id' => $jadwalDetail->id,
            'jam_mapel_id' => $request->jam_mapel_id,
        ]);

        return redirect()->route('guru-piket.jadwal.details.index', $jadwal->id)
            ->with('success', 'Detail jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal, JadwalDetail $detail)
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $gurus = Guru::orderBy('nama_guru')->get();
        $hari = $detail->hari;
        $jamMapel = $detail->jamMapel;


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

        $detail->update($request->only(['mapel_id', 'guru_id', 'hari', 'jam_mapel_id', 'jadwal_id']));

        // Update juga jam mapel di DetailJamMapel jika diperlukan
        $detailJamMapel = DetailJamMapel::where('jadwal_detail_id', $detail->id)->first();
        if ($detailJamMapel && $detailJamMapel->jam_mapel_id != $request->jam_mapel_id) {
            $detailJamMapel->update(['jam_mapel_id' => $request->jam_mapel_id]);
        }

        return redirect()->route('guru-piket.jadwal.details.index', $jadwal->id)
            ->with('success', 'Detail jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal, JadwalDetail $detail)
    {
        // Hapus detail jam mapel terlebih dahulu
        DetailJamMapel::where('jadwal_detail_id', $detail->id)->delete();

        // Kemudian hapus jadwal detail
        $detail->delete();

        return redirect()->route('guru-piket.jadwal.details.index', $jadwal->id)
            ->with('success', 'Detail jadwal berhasil dihapus.');
    }
}
