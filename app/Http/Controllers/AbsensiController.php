<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Guru;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{

    /**
     * Tampilkan daftar absensi guru dengan filter opsional berdasarkan guru dan tanggal.
     */
    public function index(Request $request)
    {
        // Ambil daftar guru untuk dropdown filter
        $gurus = Guru::orderBy('nama_guru')->get();

        // Mulai query dengan relasi agar efisien
        $query = Absensi::with(['guru', 'jadwalDetail.jadwal.kelas']);

        // Filter berdasarkan guru_id (jika ada)
        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->input('guru_id'));
        }

        // Filter berdasarkan tanggal (opsional)
        if ($request->filled('tanggal_dari')) {
            $tanggalDari = Carbon::parse($request->input('tanggal_dari'))->startOfDay();
            $query->where('waktu_absen', '>=', $tanggalDari);
        }

        if ($request->filled('tanggal_sampai')) {
            $tanggalSampai = Carbon::parse($request->input('tanggal_sampai'))->endOfDay();
            $query->where('waktu_absen', '<=', $tanggalSampai);
        }

        // Urutkan berdasarkan waktu_absen terbaru
        $absensis = $query->orderBy('waktu_absen', 'desc')->paginate(15)->withQueryString();

        // Hitung total jam kerja (jumlah pertemuan) sesuai filter tanggal dan guru (status hadir)
        $totalQuery = DB::table('absensis')
            ->join('jadwal_details', 'absensis.jadwal_detail_id', '=', 'jadwal_details.id')
            ->join('jam_mapels', 'jadwal_details.jam_mapel_id', '=', 'jam_mapels.id')
            ->where('absensis.status', 'hadir');

        if ($request->filled('guru_id')) {
            $totalQuery->where('absensis.guru_id', $request->input('guru_id'));
        }

        if ($request->filled('tanggal_dari')) {
            $tanggalDari = Carbon::parse($request->input('tanggal_dari'))->startOfDay();
            $totalQuery->where('absensis.waktu_absen', '>=', $tanggalDari);
        }

        if ($request->filled('tanggal_sampai')) {
            $tanggalSampai = Carbon::parse($request->input('tanggal_sampai'))->endOfDay();
            $totalQuery->where('absensis.waktu_absen', '<=', $tanggalSampai);
        }

        $total_jam = (int) $totalQuery->count('jam_mapels.id');

        return view('absensi.index', [
            'absensis' => $absensis,
            'gurus' => $gurus,
            'tanggalDari' => $request->tanggal_dari,
            'tanggalSampai' => $request->tanggal_sampai,
            'total_jam' => $total_jam,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Absensi::with(['guru', 'jadwalDetail.jadwal.kelas']);

        // Filter guru
        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->input('guru_id'));
        }

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $tanggalDari = Carbon::parse($request->input('tanggal_dari'))->startOfDay();
            $query->where('waktu_absen', '>=', $tanggalDari);
        }
        if ($request->filled('tanggal_sampai')) {
            $tanggalSampai = Carbon::parse($request->input('tanggal_sampai'))->endOfDay();
            $query->where('waktu_absen', '<=', $tanggalSampai);
        }

        // Hanya ambil data dengan status 'hadir'
        $query->where('status', 'hadir');

        $absensis = $query->orderBy('waktu_absen', 'desc')->get();
        $guru = null;

        if ($request->filled('guru_id')) {
            $guru = \App\Models\Guru::find($request->guru_id);
        }

        // Cek apakah ada data absen (status 'hadir')
        if ($absensis->isEmpty()) {
            return redirect()->route('guru-piket.absensi', $request->query())
                ->with('warning', 'Tidak ada data absensi dengan status "hadir" untuk diekspor.');
        }

        // Hitung total jam kerja (jumlah pertemuan) sesuai filter tanggal dan guru (status hadir)
        $totalQuery = DB::table('absensis')
            ->join('jadwal_details', 'absensis.jadwal_detail_id', '=', 'jadwal_details.id')
            ->join('jam_mapels', 'jadwal_details.jam_mapel_id', '=', 'jam_mapels.id')
            ->where('absensis.status', 'hadir');

        if ($request->filled('guru_id')) {
            $totalQuery->where('absensis.guru_id', $request->input('guru_id'));
        }

        if ($request->filled('tanggal_dari')) {
            $tanggalDari = Carbon::parse($request->input('tanggal_dari'))->startOfDay();
            $totalQuery->where('absensis.waktu_absen', '>=', $tanggalDari);
        }

        if ($request->filled('tanggal_sampai')) {
            $tanggalSampai = Carbon::parse($request->input('tanggal_sampai'))->endOfDay();
            $totalQuery->where('absensis.waktu_absen', '<=', $tanggalSampai);
        }

        $total_jam = (int) $totalQuery->count('jam_mapels.id');

        $pdf = Pdf::loadView('absensi.export-pdf', [
            'absensis' => $absensis,
            'guru' => $guru,
            'tanggalDari' => $request->tanggal_dari,
            'tanggalSampai' => $request->tanggal_sampai,
            'total_jam' => $total_jam,
        ])->setPaper('a4', 'landscape');

        $filename = 'Laporan_Absensi_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}
