<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalDetail;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HadirkanGuruController extends Controller
{
    public function index()
    {
        $today = Carbon::now('Asia/Jakarta');
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ][$today->format('l')];

        // ambil semua guru mapel yang punya jadwal hari ini tetapi belum absen
        $guruHariIni = Guru::whereHas('user', fn($q) => $q->where('role', 'guru_mapel'))
            ->whereDoesntHave('absensis', function ($q) use ($today) {
                $q->whereDate('waktu_absen', $today->toDateString());
            })
            ->get();

        return view('hadirkan.index', compact('guruHariIni', 'hari'));
    }

    public function hadirkan(Guru $guru)
    {
        $now = Carbon::now('Asia/Jakarta');
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ][$now->format('l')];

        // ambil semua jadwal hari ini milik guru
        $jadwalHariIni = JadwalDetail::where('hari', $hari)
            ->where('guru_id', $guru->id)
            ->with('jamMapel')
            ->get();

        if ($jadwalHariIni->isEmpty()) {
            return redirect()->route('guru-piket.hadirkan.index')
                ->with('info', "Guru {$guru->nama_guru} tidak memiliki jadwal hari ini.");
        }

        // tandai semua jadwal hari ini sebagai hadir (via piket)
        foreach ($jadwalHariIni as $detail) {
            Absensi::updateOrCreate(
                [
                    'guru_id' => $guru->id,
                    'jadwal_detail_id' => $detail->id,
                    'waktu_absen' => $now->toDateString(),
                ],
                [
                    'status' => 'hadir',
                    'via' => 'piket',
                    'waktu_absen' => $now,
                    'qr_token_id' => null,
                ]
            );
        }

        return redirect()->route('guru-piket.hadirkan.index')
            ->with('success', "Guru {$guru->nama_guru} berhasil ditandai hadir melalui guru piket.");
    }
}
