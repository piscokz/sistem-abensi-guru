<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\QrToken;
use App\Models\JadwalDetail;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function generate(Request $request)
    {
        $user = Auth::user();

        // ambil kelas dari user (kelas_siswa)
        $kelas = Kelas::where('user_id', $user->id)->first();
        if (!$kelas) {
            return back()->with('error', 'Kelas tidak ditemukan untuk akun ini.');
        }

        $now = Carbon::now('Asia/Jakarta');

        $map = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hari = $map[$now->format('l')];

        // ambil jadwal detail hari ini untuk kelas ini
        $jadwalDetails = JadwalDetail::whereHas('jadwal', function ($q) use ($kelas) {
            $q->where('kelas_id', $kelas->id);
        })
            ->where('hari', $hari)
            ->with('jamMapel')
            ->get();

        // cari jadwal yang sedang berlangsung
        $onGoing = $jadwalDetails->first(function ($detail) use ($now) {
            if (!$detail->jamMapel) return false;

            $mulai = Carbon::parse($detail->jamMapel->jam_mulai, 'Asia/Jakarta')
                ->setDate($now->year, $now->month, $now->day);
            $selesai = Carbon::parse($detail->jamMapel->jam_selesai, 'Asia/Jakarta')
                ->setDate($now->year, $now->month, $now->day);

            return $now->between($mulai, $selesai);
        });

        $qr = null;
        $qrImage = null;
        // dd($onGoing);

        if ($onGoing) {
            $tokenString = (string) Str::uuid();
            $jamSelesai = Carbon::parse($onGoing->jamMapel->jam_selesai, 'Asia/Jakarta')
                ->setDate($now->year, $now->month, $now->day);
            $expiresAt = $jamSelesai->copy()->addMinutes(5);

            $qr = QrToken::create([
                'token' => $tokenString,
                'jadwal_detail_id' => $onGoing->id,
                'kelas_id' => $kelas->id,
                'expires_at' => $expiresAt,
                'created_by' => $user->id,
            ]);

            // generate QR pakai Simple QrCode
            $png = QrCode::format('png')
                ->size(350)
                ->generate($qr->token);

            $qrImage = 'data:image/png;base64,' . base64_encode($png);
        }

        return view('level.kelas_siswa.qr_generate.index', compact('kelas', 'qr', 'qrImage', 'onGoing'));
    }
}
