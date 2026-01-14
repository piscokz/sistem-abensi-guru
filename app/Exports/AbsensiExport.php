<?php

namespace App\Exports;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AbsensiExport implements FromCollection, WithHeadings, WithTitle
{
    protected $filter, $start, $end;

    public function __construct($filter, $start, $end)
    {
        $this->filter = $filter;
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $start = $this->start;
        $end = $this->end;

        if (!$start || !$end) {
            $now = Carbon::now('Asia/Jakarta');
            switch ($this->filter) {
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

        return $period->map(function ($date) {
            $hadir = Absensi::whereDate('waktu_absen', $date)->where('status', 'hadir')->count();
            $tidak = Absensi::whereDate('waktu_absen', $date)->where('status', 'tidak_hadir')->count();

            return [
                'tanggal' => $date,
                'hadir' => $hadir,
                'tidak_hadir' => $tidak,
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Jumlah Hadir', 'Jumlah Tidak Hadir'];
    }

    public function title(): string
    {
        return 'Laporan Absensi Guru';
    }
}
