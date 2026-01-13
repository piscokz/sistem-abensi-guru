<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Absensi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;


class DashboardController extends Controller
{

    public function statistikAbsensi(Request $request)
    {
        $filter = $request->input('filter', 'mingguan'); // default: mingguan
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        // tentukan range otomatis berdasar filter
        if (!$start || !$end) {
            $now = Carbon::now('Asia/Jakarta');

            switch ($filter) {
                case 'harian':
                    $start = $now->format('Y-m-d');
                    $end = $now->format('Y-m-d');
                    break;
                case 'bulanan':
                    $start = $now->copy()->startOfMonth()->format('Y-m-d');
                    $end = $now->copy()->endOfMonth()->format('Y-m-d');
                    break;
                default: // mingguan
                    $start = $now->copy()->subDays(6)->format('Y-m-d');
                    $end = $now->format('Y-m-d');
                    break;
            }
        }

        // buat daftar tanggal di rentang tersebut
        $period = collect();
        $current = Carbon::parse($start);
        while ($current->lte($end)) {
            $period->push($current->format('Y-m-d'));
            $current->addDay();
        }

        // ambil data absensi per hari
        $stats = $period->map(function ($date) {
            return [
                'tanggal' => $date,
                'hadir' => Absensi::whereDate('waktu_absen', $date)->where('status', 'hadir')->count(),
                'tidak_hadir' => Absensi::whereDate('waktu_absen', $date)->where('status', 'tidak_hadir')->count(),
            ];
        });

        // total keseluruhan dalam rentang
        $totalHadir = Absensi::whereBetween(DB::raw('DATE(waktu_absen)'), [$start, $end])
            ->where('status', 'hadir')
            ->count();
        $totalTidakHadir = Absensi::whereBetween(DB::raw('DATE(waktu_absen)'), [$start, $end])
            ->where('status', 'tidak_hadir')
            ->count();

        return view('dashboard.index', compact('stats', 'totalHadir', 'totalTidakHadir', 'start', 'end', 'filter'));
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->input('filter', 'mingguan');
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        // logika sama seperti statistikAbsensi()
        if (!$start || !$end) {
            $now = Carbon::now('Asia/Jakarta');
            switch ($filter) {
                case 'harian':
                    $start = $now->format('Y-m-d');
                    $end = $now->format('Y-m-d');
                    break;
                case 'bulanan':
                    $start = $now->copy()->startOfMonth()->format('Y-m-d');
                    $end = $now->copy()->endOfMonth()->format('Y-m-d');
                    break;
                default:
                    $start = $now->copy()->subDays(6)->format('Y-m-d');
                    $end = $now->format('Y-m-d');
                    break;
            }
        }

        $period = collect();
        $current = Carbon::parse($start);
        while ($current->lte($end)) {
            $period->push($current->format('Y-m-d'));
            $current->addDay();
        }

        $stats = $period->map(function ($date) {
            return [
                'tanggal' => $date,
                'hadir' => Absensi::whereDate('waktu_absen', $date)->where('status', 'hadir')->count(),
                'tidak_hadir' => Absensi::whereDate('waktu_absen', $date)->where('status', 'tidak_hadir')->count(),
            ];
        });

        $totalHadir = Absensi::whereBetween(DB::raw('DATE(waktu_absen)'), [$start, $end])
            ->where('status', 'hadir')
            ->count();
        $totalTidakHadir = Absensi::whereBetween(DB::raw('DATE(waktu_absen)'), [$start, $end])
            ->where('status', 'tidak_hadir')
            ->count();

        $pdf = Pdf::loadView('dashboard.statistik-pdf', [
            'stats' => $stats,
            'totalHadir' => $totalHadir,
            'totalTidakHadir' => $totalTidakHadir,
            'start' => $start,
            'end' => $end,
            'filter' => $filter,
        ]);

        return $pdf->download('laporan-absensi-' . $start . '_to_' . $end . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->input('filter', 'mingguan');
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        return Excel::download(new AbsensiExport($filter, $start, $end), 'laporan-absensi.xlsx');
    }
}
