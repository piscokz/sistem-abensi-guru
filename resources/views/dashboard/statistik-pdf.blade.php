<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Semua Guru</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 40px; color: #333; }
        header { text-align: center; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        header img { width: 70px; height: 70px; margin-bottom: 5px; }
        h2 { margin: 0; }
        .info { text-align: center; font-size: 14px; margin-bottom: 30px; color: #555; }

        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: center; }
        th { background: #f1f5f9; color: #111; }

        .summary {
            display: flex; justify-content: space-around; margin: 20px 0;
            font-weight: bold;
        }
        .hadir { background: #dcfce7; color: #166534; padding: 10px 20px; border-radius: 10px; }
        .tidak { background: #fee2e2; color: #991b1b; padding: 10px 20px; border-radius: 10px; }

        footer { text-align: right; margin-top: 60px; font-size: 13px; color: #555; }
        .ttd { margin-top: 40px; text-align: right; }
        .ttd img { width: 100px; opacity: 0.8; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('logo.png') }}" alt="Logo Sekolah">
        <h2>Laporan Absensi Guru</h2>
        <p class="info">
            Periode:
            {{ \Carbon\Carbon::parse($start)->translatedFormat('d M Y') }} –
            {{ \Carbon\Carbon::parse($end)->translatedFormat('d M Y') }}
        </p>
    </header>

    <div class="summary">
        <div class="hadir">Total Hadir: {{ $totalHadir }}</div>
        <div class="tidak">Total Tidak Hadir: {{ $totalTidakHadir }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Hadir</th>
                <th>Tidak Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats as $row)
            <tr>
                <td>{{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d M Y') }}</td>
                <td>{{ $row['hadir'] }}</td>
                <td>{{ $row['tidak_hadir'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        <p>Laporan dibuat otomatis pada {{ now()->translatedFormat('d M Y H:i') }}</p>
        <div class="ttd">
            <p>Mengetahui,</p>
            <p style="margin-top: 60px; font-weight: bold;">Kepala Sekolah</p>
            {{-- <img src="{{ public_path('ttd.png') }}" alt="Tanda Tangan"> --}}
        </div>
    </footer>
</body>
</html>
