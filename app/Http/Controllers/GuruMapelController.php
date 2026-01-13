<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalDetail;
use App\Models\JamMapel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\QrToken;
use App\Models\Absensi;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class GuruMapelController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::user()->id)->first();
        $nama_guru = $guru->nama_guru;

        // Ambil semua jadwal detail untuk guru yang dipilih
        $jadwalDetails = JadwalDetail::where('guru_id', $guru->id)
            ->with([
                'jadwal.shift',     // relasi ke shift
                'jadwal.kelas',     // relasi ke kelas
                'mapel',            // relasi ke mapel
                'jamMapel'          // relasi ke jam mapel
            ])
            ->get();

        // Kelompokkan berdasarkan shift
        $groupedByShift = $jadwalDetails
            ->groupBy(fn($detail) => $detail->jadwal->shift->id)
            ->map(function ($items) {
                $shift = $items->first()->jadwal->shift;
                return [
                    'shift' => $shift,
                    'details' => $items,
                ];
            });

        $namaKelas = $jadwalDetails->map(function ($detail) {
            return $detail->jadwal->kelas->nama_kelas;
        })->unique()->values()->all();


        // dd($nama_guru, $shift, $jamMapel, $namaKelas, $jadwalDetails);
        return view('level.guru_mapel.jadwal.index', compact('nama_guru', 'namaKelas', 'groupedByShift',));
    }

    // halaman scanner
    public function scanPage()
    {
        return view('level.guru_mapel.scan_qr.index');
    }

    public function absen(Request $request)
    {
        $token = $request->query('token');
        $kelas_id = QrToken::where('token', $token)->value('kelas_id');
        $jadwal_id = Jadwal::where('kelas_id', $kelas_id)->value('id');

        if (!$token) {
            return redirect()->route('guru-mapel.scan-qr.index')
                ->with('error', 'Token QR tidak ditemukan.');
        }

        $qr = QrToken::where('token', $token)->first();
        if (!$qr) {
            return redirect()->route('guru-mapel.scan-qr.index')
                ->with('error', 'Token QR tidak valid.');
        }


        $dateTime = Carbon::now('Asia/Jakarta')->locale('id');
        $hari = $dateTime->dayName;
        $waktu = $dateTime->format('H:i:s');

        $guru = Guru::where('user_id', Auth::user()->id)->first();
        if (!$guru) {
            return redirect()->route('guru-mapel.scan-qr.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }

        // cari jadwal aktif (jam sekarang)
        $jadwalAktif = JadwalDetail::where('hari', $hari)
            ->where('guru_id', $guru->id)
            ->whereHas('jamMapel', function ($q) use ($waktu) {
                $q->where('jam_mulai', '<=', $waktu)
                    ->where('jam_selesai', '>=', $waktu);
            })
            ->with(['jamMapel', 'mapel'])
            ->first();

        if (!$jadwalAktif) {
            return redirect()->route('guru-mapel.scan-qr.index')
                ->with('error', 'Tidak ada jadwal aktif untuk Anda saat ini.');
        }

        // ambil semua jadwal guru hari ini dengan mapel yang sama
        $jadwalMapelHariIni = JadwalDetail::where('hari', $hari)
            ->where('jadwal_id', $jadwal_id)
            ->where('guru_id', $guru->id)
            ->where('mapel_id', $jadwalAktif->mapel_id)
            ->with('jamMapel')
            ->get();

        foreach ($jadwalMapelHariIni as $detail) {
            Absensi::updateOrCreate(
                [
                    'guru_id' => $guru->id,
                    'jadwal_detail_id' => $detail->id,
                ],
                [
                    'qr_token_id' => $qr->id,
                    'status' => 'hadir',
                    'waktu_absen' => $dateTime,
                ]
            );
        }

        // hapus absensi "tidak_hadir" sebelumnya untuk mapel & guru ini di hari yang sama
        Absensi::where('guru_id', $guru->id)
            ->whereIn('jadwal_detail_id', $jadwalMapelHariIni->pluck('id'))
            ->where('status', 'tidak_hadir')
            ->whereDate('waktu_absen', Carbon::today('Asia/Jakarta'))
            ->delete();

        $mapel = $jadwalAktif->mapel->nama_mapel;
        $jamAwal = $jadwalMapelHariIni->first()->jamMapel->nomor_jam ?? '-';
        $jamAkhir = $jadwalMapelHariIni->last()->jamMapel->nomor_jam ?? '-';

        return redirect()->route('guru-mapel.rekap-absensi')
            ->with('success', "Absensi berhasil! Semua jam mapel {$mapel} hari ini (jam ke-{$jamAwal}–{$jamAkhir}) ditandai hadir.");
    }




    public function validateToken($token)
    {
        $user = Auth::user();
        $guru = \App\Models\Guru::where('user_id', $user->id)->firstOrFail();
        $now = Carbon::now('Asia/Jakarta');

        $qr = QrToken::where('token', $token)
            ->with(['kelas', 'jadwalDetail.mapel', 'jadwalDetail.jamMapel'])
            ->first();

        if (!$qr) {
            return view('level.guru_mapel.scan_qr.result', [
                'status' => 'error',
                'message' => 'Token QR tidak ditemukan.'
            ]);
        }

        if (!$qr->isValid()) {
            return view('level.guru_mapel.scan_qr.result', [
                'status' => 'error',
                'message' => 'Token sudah kadaluarsa atau telah digunakan.'
            ]);
        }

        $jadwalDetail = $qr->jadwalDetail;
        $hari = $jadwalDetail->hari;
        $mapelId = $jadwalDetail->mapel_id;

        // ambil semua jadwal berurutan dengan mapel & guru sama di hari yang sama
        $semuaJam = JadwalDetail::where('hari', $hari)
            ->where('guru_id', $guru->id)
            ->where('mapel_id', $mapelId)
            ->whereHas('jamMapel', function ($q) use ($jadwalDetail) {
                $q->whereBetween('nomor_jam', [
                    $jadwalDetail->jamMapel->nomor_jam,
                    $jadwalDetail->jamMapel->nomor_jam + 1
                ]);
            })
            ->get();

        // filter hanya jadwal yang belum diabsen hari ini
        $sudahAbsen = Absensi::where('guru_id', $guru->id)
            ->whereDate('waktu_absen', $now->toDateString())
            ->pluck('jadwal_detail_id')
            ->toArray();

        $belumAbsen = $semuaJam->reject(fn($d) => in_array($d->id, $sudahAbsen));

        if ($belumAbsen->isEmpty()) {
            return view('level.guru_mapel.scan_qr.result', [
                'status' => 'error',
                'message' => 'Anda sudah absen untuk jam ini.'
            ]);
        }

        // catat absensi untuk semua jadwal berurutan yang belum diabsen
        foreach ($belumAbsen as $detail) {
            Absensi::create([
                'guru_id' => $guru->id,
                'jadwal_detail_id' => $detail->id,
                'waktu_absen' => $now,
                'qr_token_id' => $qr->id,
                'status' => 'hadir',
            ]);
        }

        // tandai token terpakai
        $qr->used = true;
        $qr->save();

        return view('level.guru_mapel.scan_qr.result', [
            'status' => 'success',
            'message' => 'Absensi berhasil direkam.',
            'guru' => $guru,
            'kelas' => $qr->kelas,
            'mapel' => $qr->jadwalDetail->mapel,
            'jam' => $belumAbsen->pluck('jamMapel.nomor_jam')->implode(', ')
        ]);
    }


    public function rekapAbsensi()
    {
        $user = Auth::user();
        $guru = \App\Models\Guru::where('user_id', $user->id)->firstOrFail();

        $hariSekarang = Carbon::now('Asia/Jakarta')->format('l');
        $map = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $hari = $map[$hariSekarang];

        // ambil semua jadwal guru hari ini
        $jadwalHariIni = \App\Models\JadwalDetail::where('guru_id', $guru->id)
            ->where('hari', $hari)
            ->with(['mapel', 'jamMapel', 'jadwal.kelas'])
            ->get();

        // ambil semua absensi hari ini (hadir / tidak_hadir)
        $absensiHariIni = \App\Models\Absensi::where('guru_id', $guru->id)
            ->whereDate('waktu_absen', Carbon::today('Asia/Jakarta'))
            ->get()
            ->keyBy('jadwal_detail_id'); // supaya bisa akses cepat per jadwal_detail_id

        // gabungkan data jadwal + status absen aktual
        $rekap = $jadwalHariIni->map(function ($detail) use ($absensiHariIni) {
            $absen = $absensiHariIni->get($detail->id);

            return [
                'jadwal_detail_id' => $detail->id,
                'mapel' => $detail->mapel->nama_mapel,
                'kelas' => $detail->jadwal->kelas->nama_kelas,
                'jam' => $detail->jamMapel->nomor_jam,
                'status' => $absen ? ucfirst($absen->status) : 'Belum Diisi',
                'via' => $absen->via ?? '-',
                'waktu_absen' => $absen->waktu_absen ?? '-',
            ];
        })->sortBy('jam')->values();

        return view('level.guru_mapel.absensi.rekap', compact('guru', 'hari', 'rekap'));
    }
}
