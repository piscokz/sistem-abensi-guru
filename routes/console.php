<?php

use Illuminate\Foundation\Inspiring;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // <-- PENTING BUAT Task Schedule
use Illuminate\Support\Facades\Log;
use App\Models\Absensi;
use App\Models\JadwalDetail;
use App\Models\TanggalMerah;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $now = Carbon::now('Asia/Jakarta');
    $today = $now->toDateString();

    // Cek apakah hari ini libur
    if (TanggalMerah::whereDate('tanggal', $today)->exists()) {
        Log::info("🛑 Hari ini ($today) adalah tanggal merah — task absensi dilewati.");
        echo "\033[33m🛑 Hari ini ($today) libur. Task absensi dilewati.\033[0m\n";
        return;
    }

    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ][$now->format('l')];

    $jadwalHariIni = JadwalDetail::where('hari', $hari)
        ->with('jamMapel')
        ->get();

    foreach ($jadwalHariIni as $detail) {
        // $jamSelesai = Carbon::parse($detail->jamMapel->jam_selesai, 'Asia/Jakarta');
        $jamSelesai = Carbon::today('Asia/Jakarta')
            ->setTimeFromTimeString($detail->jamMapel->jam_selesai);


        // hanya jalankan 5–60 menit setelah jam selesai
        $batasAwal = $jamSelesai->copy()->addMinutes(5);
        $batasAkhir = $jamSelesai->copy()->addHour();

        $sudahAbsen = Absensi::where('guru_id', $detail->guru_id)
            ->where('jadwal_detail_id', $detail->id)
            ->whereDate('waktu_absen', $now->toDateString())
            ->exists();

        Log::info("DEBUG jadwal {$detail->id}: jam_selesai={$jamSelesai->format('H:i:s')} now={$now->format('H:i:s')} absen=" . ($sudahAbsen ? 'ya' : 'tidak'));

        if (!$sudahAbsen && $now->gt($jamSelesai)) {
            Absensi::create([
                'guru_id' => $detail->guru_id,
                'jadwal_detail_id' => $detail->id,
                'waktu_absen' => $jamSelesai, // simpan waktu selesai biar jelas
                'qr_token_id' => null,
                'status' => 'tidak_hadir',
                'via' => 'otomatis',
            ]);
            Log::info("⏰ [AUTO] Guru ID {$detail->guru_id} ditandai TIDAK HADIR untuk jadwal detail ID {$detail->id} ({$hari})");
        }
    }

    echo "\033[32m✅ Task absensi otomatis selesai dijalankan pada {$now->format('H:i:s')} ({$hari})\033[0m\n";
    Log::info("✅ Task absensi otomatis selesai dijalankan pada {$now}");
})->everyMinute(); // sementara dijalankan setiap menit
    // setiap 10 menit, dari jam 6 pagi sampai 11 malam
    // ->cron('*/10 6-23 * * *');
