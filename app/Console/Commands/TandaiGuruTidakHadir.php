<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalDetail;
use App\Models\Absensi;
use Carbon\Carbon;

class TandaiGuruTidakHadir extends Command
{
    protected $signature = 'absensi:tandai:tidak:hadir';
    protected $description = 'Menandai guru yang tidak absen pada jadwal hari ini sebagai tidak hadir.';

    public function handle() {
        Absensi::create([
            'guru_id' => 1,
            'jadwal_detail_id' => 1,
            'waktu_absen' => Carbon::now('Asia/Jakarta'),
            'qr_token_id' => null,
            'status' => 'tidak_hadir',
            'via' => 'otomatis',
        ]);
        $this->info('Guru yang tidak absen telah ditandai.');
    }
    // public function handle()
    // {
    //     $now = Carbon::now('Asia/Jakarta');
    //     $hari = [
    //         'Sunday' => 'Minggu',
    //         'Monday' => 'Senin',
    //         'Tuesday' => 'Selasa',
    //         'Wednesday' => 'Rabu',
    //         'Thursday' => 'Kamis',
    //         'Friday' => 'Jumat',
    //         'Saturday' => 'Sabtu'
    //     ][$now->format('l')];

    //     $jadwalHariIni = JadwalDetail::where('hari', $hari)
    //         ->with('jamMapel')
    //         ->get();
    //         // cetak ke terminal

    //     foreach ($jadwalHariIni as $detail) {
            

    //         $now = Carbon::now('Asia/Jakarta');

    //         // lewati kalau jam belum selesai
    //         if (!$now->gt(Carbon::parse($detail->jamMapel->jam_selesai, 'Asia/Jakarta'))) {
    //             continue;
    //         }

    //         // skip jika sudah ada absensi (hadir / tidak hadir)
    //         $sudahAdaAbsensi = Absensi::where('jadwal_detail_id', $detail->id)
    //             ->whereDate('waktu_absen', $now->toDateString())
    //             ->exists();

    //         if (!$sudahAdaAbsensi) {
    //             Absensi::create([
    //                 'guru_id' => $detail->guru_id,
    //                 'jadwal_detail_id' => $detail->id,
    //                 'waktu_absen' => $now,
    //                 'qr_token_id' => null,
    //                 'status' => 'tidak_hadir',
    //                 'via' => 'otomatis',
    //             ]);
    //         }
    //     }
    //     $this->info('Guru yang tidak absen telah ditandai.');

    // }
}
