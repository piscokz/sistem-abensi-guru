<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Guru</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; margin: 20px; color: #333; }
        h2, h4 { text-align: center; margin: 0; }
        h4 { margin-bottom: 20px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: center; font-size: 11px; }
        th { background-color: #f3f4f6; }
        .status-hadir { background: #dcfce7; color: #166534; }
        .status-tidak { background: #fee2e2; color: #991b1b; }
        footer { text-align: right; font-size: 10px; margin-top: 30px; color: #666; }
    </style>
</head>
<body>
    <h2>Laporan Absensi Guru</h2>
    <h4>
        @if($guru)
            Guru: {{ $guru->nama_guru }} <br>
        @endif
        Periode:
        {{ $tanggalDari ? \Carbon\Carbon::parse($tanggalDari)->translatedFormat('d M Y') : '-' }}
        semua {{ $tanggalSampai ? \Carbon\Carbon::parse($tanggalSampai)->translatedFormat('d M Y') : '-' }}
    </h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Guru</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Kelas</th>
                <th>Via</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensis as $index => $absen)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $absen->guru->nama_guru ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($absen->waktu_absen)->format('d M Y') }}</td>
                    <td>
                        {{ $absen->jadwalDetail->jamMapel->nomor_jam ?? '-' }}
                        ({{ $absen->jadwalDetail->jamMapel->jam_mulai ?? '-' }} -
                        {{ $absen->jadwalDetail->jamMapel->jam_selesai ?? '-' }})
                    </td>
                    <td>{{ $absen->jadwalDetail->jadwal->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ ucfirst($absen->via) }}</td>
                    <td class="{{ $absen->status === 'hadir' ? 'status-hadir' : 'status-tidak' }}">
                        {{ ucfirst($absen->status) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Dicetak pada {{ now()->translatedFormat('d M Y H:i') }}
    </footer>
</body>
</html>
